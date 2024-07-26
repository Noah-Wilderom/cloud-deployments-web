<?php

namespace Modules\Cloud\Observers;

use Modules\Cloud\Enums\ServerStatus;
use Modules\Cloud\Events\ServerUpdated;
use Modules\Cloud\Jobs\ProvisionServer;
use Modules\Cloud\Models\Server;

class ServerObserver
{
    /**
     * Handle the InitializeServerObserver "created" event.
     */
    public function created(Server $server): void
    {
        //
    }

    /**
     * Handle the InitializeServerObserver "updated" event.
     */
    public function updated(Server $server): void
    {
        broadcast(new ServerUpdated($server));
        if($server->status === ServerStatus::Dispatched) {
            $server->updateQuietly(["status" => ServerStatus::Provisioning]);
            ProvisionServer::dispatch($server);
        }
    }

    /**
     * Handle the InitializeServerObserver "deleted" event.
     */
    public function deleted(Server $server): void
    {
        //
    }
}
