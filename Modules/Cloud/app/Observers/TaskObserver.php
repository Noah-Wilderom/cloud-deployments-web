<?php

namespace Modules\Cloud\Observers;

use Modules\Cloud\Events\ServerTaskCreated;
use Modules\Cloud\Events\ServerTaskUpdated;
use Modules\Cloud\Models\Task;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        event(new ServerTaskCreated($task));
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        event(new ServerTaskUpdated($task));
    }
}
