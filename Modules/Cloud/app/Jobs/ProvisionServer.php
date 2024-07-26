<?php

namespace Modules\Cloud\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Cloud\Enums\ServerStatus;
use Modules\Cloud\Models\Server;

class ProvisionServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private Server $server,
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->provision();
        } catch (\Exception $e) {
            $this->server->update(["status" => ServerStatus::Failed]);
            throw $e;
        }
    }

    public function provision(): void {
        // TODO: a lot of logic to execute the scripts one by one
    }
}
