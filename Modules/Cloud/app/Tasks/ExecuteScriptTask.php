<?php

namespace Modules\Cloud\Tasks;

use Illuminate\Support\Facades\Storage;
use Modules\Cloud\Enums\Software;
use Modules\Cloud\Models\Project;

abstract class ExecuteScriptTask extends Task {
    abstract function path(): string;
    abstract function name(): string;


    /**
     * @return array<string, mixed>
     */
    public function data(): array {
        return [];
    }
    public function command(): string {
        $fileContents = Storage::disk("scripts")->get($this->path());
        return $this->replaceVariables($fileContents, $this->data());
    }
}
