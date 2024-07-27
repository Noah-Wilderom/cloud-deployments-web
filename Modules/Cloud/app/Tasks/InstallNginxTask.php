<?php

namespace Modules\Cloud\Tasks;

use Illuminate\Support\Facades\Storage;
use Modules\Cloud\Enums\Software;

class InstallNginxTask extends InstallSoftwareTask {
    protected Software $software = Software::Nginx;

    public function versions(): array {
        return $this->software->versions();
    }
}
