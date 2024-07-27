<?php

namespace Modules\Cloud\Tasks;

use Illuminate\Support\Facades\Storage;

class InitServerTask extends Task {
    public function name(): string {
        return "Init Server";
    }
    public function command(): string
    {
        $path = "server/initialization/init.sh";
        $script = Storage::disk("scripts")->get($path);
        if (is_null($script)) {
            throw new \Exception(sprintf("Script not found at: %s", Storage::disk("scripts")->path($path)));
        }

        return sprintf("echo '%s' | sh", $script);
    }
}
