<?php

namespace Modules\Cloud\Tasks;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SetupNginxTask extends ExecuteScriptTask {

    /**
     * {@inheritDoc}
     */
    public function data(): array {
        return [
            "USER_NAME" => $this->project->ssh_user,
            "DOMAIN" => $this->project->getFullDomain(),
            "PHP_VERSION" => "8.3",
        ];
    }
    function path(): string {
        return "project/setup_nginx.sh";
    }

    function name(): string {
        return "Preparing Nginx";
    }
}
