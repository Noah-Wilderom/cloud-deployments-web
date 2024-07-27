<?php

namespace Modules\Cloud\Tasks;

use Illuminate\Support\Facades\Storage;
use Modules\Cloud\Enums\Software;

class InstallComposerTask extends InstallSoftwareTask {
    protected Software $software = Software::Composer;

    public function versions(): array {
        return $this->software->versions();
    }
}
