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

        foreach($softwareStack as $software) {
            $this->server
                ->runTask($software->getTask(), new ServerTaskLog($this->server))
                ->asRoot()
                ->handle();
        }
//        try {
//            // TODO: a lot of logic to execute the scripts one by one
//            Log::info("ProvisionServer job started.");
//            $files = Storage::disk("scripts")->allFiles("server/initialization");
//            foreach ($files as $file) {
//                Log::info('Executing script.', ['file' => $file]);
//                $sshConnection = new SSH\Connection(
//                    host: $this->server->host["ipv4"],
//                    port: 22,
//                    username: "root",
//                    privateKeyPath: sprintf("%s/id_rsa", $this->server->ssh_credentials_path),
//                    timeout: $this->timeout,
//                    event: new ServerInitializingLogs($this->server),
//                    logFileId: $this->server->getKey(),
//                );
//
//                $sshConnection->executeShellScript($file);
//                Log::info('Script executed successfully.', ['file' => $file]);
//            }
//        } catch (\Exception $e) {
//            Log::error('ProvisionServer job failed.', ['exception' => $e->getMessage()]);
//            throw $e;
//        }
    }

    public function failed(Throwable $exception)
    {
        $this->server->update(["status" => ServerStatus::Failed]);
    }
}
