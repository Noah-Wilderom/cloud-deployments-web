<?php

namespace Modules\Cloud\Events\SSH;

use App\Events\SSHLogStreamBase;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Cloud\Models\Project;
use Modules\Cloud\Models\Server;
use Modules\Cloud\Models\Task;

class ProjectTaskLog extends SSHLogStreamBase implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Project $project,
    )
    {
        //
    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("ssh-logs.project.{$this->project->getKey()}.task.{$this->task->getKey()}"),
        ];
    }
}
