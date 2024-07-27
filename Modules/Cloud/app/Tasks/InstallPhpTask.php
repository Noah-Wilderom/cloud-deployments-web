<?php

namespace Modules\Cloud\Tasks;

use Illuminate\Support\Facades\Storage;
use Modules\Cloud\Enums\Software;

class InstallPhpTask extends InstallSoftwareTask {
    protected Software $software = Software::Php;

    public function versions(): array {
        return $this->software->versions();
    }
}
