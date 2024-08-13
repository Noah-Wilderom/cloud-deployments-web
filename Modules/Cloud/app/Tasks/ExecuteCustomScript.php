<?php

namespace Modules\Cloud\Tasks;


use Illuminate\Support\Facades\Storage;

class ExecuteCustomScript extends CommandTask {
    public function name(): string {
        return "Execute custom script";
    }
    public function command(): string {
        return sprintf('sudo -u %s bash -c "%s"', $this->project->credentials->user);
    }
}
