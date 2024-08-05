<?php

namespace Modules\Cloud\Tasks;

use Illuminate\Support\Facades\Storage;
use Modules\Cloud\Enums\Software;

abstract class InstallSoftwareTask extends Task {
    protected Software $software;

    public abstract function versions(): array;
    public function command(): string {
        $path = $this->software->getScriptPath();
        $script = Storage::disk("scripts")->get($path);
        if (is_null($script)) {
            throw new \Exception(sprintf("Script not found at: %s", Storage::disk("scripts")->path($path)));
        }

        $script = $this->replaceVariables($script, [
            "VERSION" => "8.3", // TODO: run the task multiple times to get the version dynamically?
        ]);

        return sprintf("echo '%s' | sh", $script);
    }

    public function name(): string {
        return sprintf("Install %s", $this->software->displayName());
    }

    public function callback(bool $commandSuccess): void {
        if (! $commandSuccess) {
            return;
        }

        $this->server->softwares()->create([
            "task_id" => $this->task->getKey(),
            "software" => $this->software,
        ]);
    }
}
