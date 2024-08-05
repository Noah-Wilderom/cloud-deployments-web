<?php

namespace Modules\Cloud\Tasks;

use Illuminate\Support\Facades\Storage;
use Modules\Cloud\Enums\Software;

class InstallDockerTask extends InstallSoftwareTask {
    protected Software $software = Software::Docker;

    public function versions(): array {
        return $this->software->versions();
    }
}
