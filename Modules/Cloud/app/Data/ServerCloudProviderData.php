<?php

namespace Modules\Cloud\Data;

use Spatie\LaravelData\Data;

class ServerCloudProviderData extends Data
{
    public function __construct(
        public string $id,
        public string $type,
        public string $iso,
        public string $sshKeyId,
    ) {}
}
