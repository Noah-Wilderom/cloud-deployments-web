<?php

namespace Modules\Cloud\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Cloud\Enums\ServerStatus;
use Modules\Cloud\Enums\Software;
use Modules\Cloud\Events\SSH\ServerInitializingLogs;
use Modules\Cloud\Events\SSH\ServerTaskLog;
use Modules\Cloud\Models\Server;
use App\SSH;
use Modules\Cloud\Tasks\InitServerTask;
use Throwable;

class ProvisionServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 60 * 15; // 15 minutes
    public $tries = 1;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Server $server,
    )
    {
        Log::info("ProvisionServer job instance created.");
    }

    /**
     * Execute the job.
     * @throws \Exception
     */
    public function handle(): void
    {
        $softwareStack = Software::defaultStack($this->server->type);

        $this->server
            ->runTask(InitServerTask::class, new ServerTaskLog($this->server))
            ->asRoot()
            ->handle();

        foreach($softwareStack as $software) {
            try {
                $this->server
                    ->runTask($software->getTask(), new ServerTaskLog($this->server))
                    ->asRoot()
                    ->handle();
            } catch(\Exception $e) {
                Log::info("ProvisionServer job has failed", ["server" => $this->server->getKey(), "software" => $software->name]);
                throw $e;
            }
        }

        $this->server->update(["status" => ServerStatus::Online]);
    }

    public function failed(Throwable $exception)
    {
        $this->server->update(["status" => ServerStatus::Failed]);
    }
}
