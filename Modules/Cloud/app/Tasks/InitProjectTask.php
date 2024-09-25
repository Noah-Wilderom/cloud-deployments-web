<?php

namespace Modules\Cloud\Tasks;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InitProjectTask extends ExecuteScriptTask {

    /**
     * {@inheritDoc}
     */
    public function data(): array {
        $publicKeyContents = Storage::disk("keypairs")->get(sprintf("%s/id_rsa.pub", $this->project->host_ssh_credentials_path));
        $publicKeyContents = Crypt::decryptString($publicKeyContents);

        $privKeyContents = Storage::disk("keypairs")->get(sprintf("%s/id_rsa", $this->project->host_ssh_credentials_path));
        $privKeyContents = Crypt::decryptString($privKeyContents);

        return [
            "USER_NAME" => $this->project->ssh_user,
            "USER_PASSWORD" => Str::random(),
            "SSH_PUB_KEY" => $publicKeyContents,
            "SSH_PRIV_KEY" => $privKeyContents,
        ];
    }
    function path(): string {
        return "project/init.sh";
    }

    function name(): string {
        return "Initializing project";
    }
}
