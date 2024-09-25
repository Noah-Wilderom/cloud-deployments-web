<?php

namespace Modules\Cloud\Tasks;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CloneRepositoryTask extends ExecuteScriptTask {

    /**
     * {@inheritDoc}
     */
    public function data(): array {
        return [
            "USER_NAME" => $this->project->ssh_user,
            "GITHUB_SSH" => $this->project->git_repository->sshCloneUrl,
        ];
    }
    function path(): string {
        return "project/clone_repository.sh";
    }

    function name(): string {
        return "Cloning repository";
    }
}
