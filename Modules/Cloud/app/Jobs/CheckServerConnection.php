<?php

namespace Modules\Cloud\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Modules\Cloud\Enums\ServerStatus;
use Modules\Cloud\Models\Server;
use App\SSH;

class CheckServerConnection implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Collection $servers;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->servers = Server::query()
            ->where("status", ServerStatus::Created)
            ->get();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach($this->servers as $server) {
            $server = $server->refresh();
            if ($server->status !== ServerStatus::Created) {
                continue;
            }

            $sshConnection = new SSH\Connection(
                host: $server->host["ipv4"],
                port: 22,
                username: "root",
                privateKeyPath: sprintf("%s/id_rsa", $server->ssh_credentials_path),
            );

            if($sshConnection->ping()) {
                $server->update(["status" => ServerStatus::Dispatched]);
            }
        }
    }
}
