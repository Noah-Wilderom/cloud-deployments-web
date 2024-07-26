<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use phpseclib3\Crypt\RSA;

class SSHService {

    public function __construct() {

    }

    public function generateKeyPair(string $privateKeyPath, string $publicKeyPath): bool {
        // Generate the RSA key pair
        $key = RSA::createKey(4096);

        $privateKey = $key->toString('PKCS8');
        Storage::disk("keypairs")->put($privateKeyPath, $privateKey);

        $publicKey = $key->getPublicKey()->toString('OpenSSH', ['comment' => "cloud-deployments.dev"]);
        Storage::disk("keypairs")->put($publicKeyPath, $publicKey);

        return true;
    }
}
