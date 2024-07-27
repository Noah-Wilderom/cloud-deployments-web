<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Modules\Cloud\Models\Task;

abstract class SSHLogStreamBase
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $logLine;
    public ?Task $task = null;
}
