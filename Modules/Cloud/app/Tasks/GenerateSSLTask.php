<?php

namespace Modules\Cloud\Tasks;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateSSLTask extends ExecuteScriptTask {

    /**
     * {@inheritDoc}
     */
    public function data(): array {
        $this->project->loadMissing(["user"]);
        return [
            "DOMAIN" => $this->project->getFullDomain(),
            "AUTH_EMAIL" => $this->project->user->email,
        ];
    }
    function path(): string {
        return "project/generate_ssl.sh";
    }

    function name(): string {
        return "Generating SSL";
    }
}
