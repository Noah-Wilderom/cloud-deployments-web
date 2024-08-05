<?php

namespace Modules\Cloud\Tasks;

use Illuminate\Support\Facades\Storage;
use Modules\Cloud\Enums\Software;

class InstallGoTask extends InstallSoftwareTask {
    protected Software $software = Software::Go;

    public function versions(): array {
        return $this->software->versions();
    }
}
