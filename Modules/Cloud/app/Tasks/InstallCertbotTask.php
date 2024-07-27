<?php

namespace Modules\Cloud\Tasks;

use Illuminate\Support\Facades\Storage;
use Modules\Cloud\Enums\Software;

class InstallCertbotTask extends InstallSoftwareTask {
    protected Software $software = Software::Certbot;

    public function versions(): array {
        return $this->software->versions();
    }
}
