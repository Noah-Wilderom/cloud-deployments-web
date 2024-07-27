<?php

namespace Modules\Cloud\Tasks;

use Illuminate\Support\Facades\Storage;
use Modules\Cloud\Enums\Software;

class InstallMySqlTask extends InstallSoftwareTask {
    protected Software $software = Software::MySql;

    public function versions(): array {
        return $this->software->versions();
    }
}
