<?php

namespace Modules\Cloud\Tasks;


use Illuminate\Support\Facades\Storage;

class AddPublicKeyTask extends CommandTask {
    const PUBLIC_KEY_DATA = "public_key";

    public function name(): string {
        return "Add Public Key";
    }
    public function command(): string {
        return sprintf("mkdir -p ~/.ssh && echo '%s' >> ~/.ssh/authorized_keys", $this->data[self::PUBLIC_KEY_DATA]);
    }
}
