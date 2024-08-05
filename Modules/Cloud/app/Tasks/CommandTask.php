<?php

namespace Modules\Cloud\Tasks;

use Illuminate\Support\Facades\Storage;
use Modules\Cloud\Enums\Software;
use Modules\Cloud\Models\Project;

abstract class CommandTask extends Task {
    abstract function command(): string;
    abstract function name(): string;
}
