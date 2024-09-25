<?php

namespace Modules\Cloud\Tasks;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FinishNginxTask extends ExecuteScriptTask {

    /**
     * {@inheritDoc}
     */
    public function data(): array {
        return [
            "DOMAIN" => $this->project->getFullDomain(),
        ];
    }
    function path(): string {
        return "project/finish_nginx.sh";
    }

    function name(): string {
        return "Finish Nginx";
    }
}
