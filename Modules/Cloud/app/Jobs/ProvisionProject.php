<?php

namespace Modules\Cloud\Jobs;

use App\Enums\IntegrationProvider;
use Github\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Cloud\Events\SSH\ProjectTaskLog;
use Modules\Cloud\Events\SSH\ServerTaskLog;
use Modules\Cloud\Models\Project;
use Modules\Cloud\Tasks\AddPublicKeyTask;
use Modules\Cloud\Tasks\CloneRepositoryTask;
use Modules\Cloud\Tasks\FinishNginxTask;
use Modules\Cloud\Tasks\GenerateSSLTask;
use Modules\Cloud\Tasks\InitProjectTask;
use Modules\Cloud\Tasks\InitServerTask;
use Modules\Cloud\Tasks\SetupNginxTask;
use Modules\Cloud\Tasks\SetupProjectPermissionsTask;

class ProvisionProject implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Project $project
    )
    {
        $this->project->loadMissing(["user", "server"]);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $publicKeyContents = Storage::disk("keypairs")->get(sprintf("%s/id_rsa.pub", $this->project->host_ssh_credentials_path));
        $publicKeyContents = Crypt::decryptString($publicKeyContents);

        Log::info("Project {$this->project->getKey()}", [$this->project->git_repository]);
        IntegrationProvider::Github->getIntegrationService($this->project->user)
            ->service()
            ->repo()
            ->keys()
            ->create(
                $this->project->git_repository->username,
                $this->project->git_repository->repository,
                [
                    "title" => $this->project->ssh_user,
                    "key" => $publicKeyContents,
                ]
            );

        $tasks = [
            $this->project->runTask(InitProjectTask::class, new ProjectTaskLog($this->project)),
            $this->project->runTask(CloneRepositoryTask::class, new ProjectTaskLog($this->project)),
            $this->project->runTask(SetupProjectPermissionsTask::class, new ProjectTaskLog($this->project)),
            $this->project->runTask(SetupNginxTask::class, new ProjectTaskLog($this->project)),
            $this->project->runTask(GenerateSSLTask::class, new ProjectTaskLog($this->project)),
            $this->project->runTask(FinishNginxTask::class, new ProjectTaskLog($this->project)),
        ];

        foreach($tasks as $task) {
            try {
                $task->asRoot()
                    ->handle();
            } catch(\Exception $e) {
                Log::info("ProvisionProject job has failed", ["project" => $this->project->getKey(), "task" => $task::class]);
                throw $e;
            }
        }

        if($this->project->user->keys()->count() > 0) {
            $key = $this->project->user->primaryKey();
            $publicKey = Crypt::decryptString(Storage::disk("keypairs")->get($key->path));

            $this->project
                ->runTask(AddPublicKeyTask::class, data: [AddPublicKeyTask::PUBLIC_KEY_DATA => $publicKey])
                ->asRoot()
                ->handle();
        }
    }
}
