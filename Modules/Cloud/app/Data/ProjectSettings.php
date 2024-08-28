<?php

namespace Modules\Cloud\Data;

use App\Enums\IntegrationProvider;
use App\Models\User;
use Github\Client;
use Github\Exception\RuntimeException;
use Spatie\LaravelData\Data;

class ProjectSettings extends Data
{
    public function __construct(
        public string $prodGitBranch = "master",
        public bool $gitAutoDeploy = false,
        public array $autoDeployEnvironments = [],
    ) {}
}
