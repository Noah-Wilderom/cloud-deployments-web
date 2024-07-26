<?php

namespace App\SSH;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use phpseclib3\Crypt\RSA;

class KeyPairGenerator {
    private int $keySize;
    private string $keyComment;

    public function __construct($keySize = 4096, $keyComment = 'cloud-deployments.dev') {
        $this->keySize = $keySize;
        $this->keyComment = $keyComment;
    }
    public function generateKeyPair(string $privateKeyPath, string $publicKeyPath): bool {
        // Generate the RSA key pair
        $key = RSA::createKey($this->keySize);

        $privateKey = $key->toString('PKCS8');
        Storage::disk("keypairs")->put($privateKeyPath, Crypt::encryptString($privateKey));

        $publicKey = $key->getPublicKey()->toString('OpenSSH', ['comment' => $this->keyComment]);
        Storage::disk("keypairs")->put($publicKeyPath, Crypt::encryptString($publicKey));

        return true;
    }
}
