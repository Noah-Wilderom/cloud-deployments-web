<?php

namespace App\Integrations;

use App\Enums\IntegrationProvider;
use App\Models\UserIntegration;
use App\Services\CloudflareClient;
use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use LKDev\HetznerCloud\HetznerAPIClient;

class CloudflareIntegration extends Integration {

    public function connectUrl(): ?string {
        return route("cloudflare.connect");
    }

    public function setToken(string $token): ?UserIntegration {
        return UserIntegration::create([
            "user_id" => $this->user->getKey(),
            "provider" => IntegrationProvider::Cloudflare,
            "token" => $token,
            "data" => []
        ]);
    }
    public function verifyToken(string $token): bool {
        return (new CloudflareClient($token))->verify();
    }

    public function service(): ?object
    {
        $token = $this->user->getIntegration(IntegrationProvider::Cloudflare)?->token;
        if ($token === null) {
            return null;
        }

        return new CloudflareClient($token);
    }
}
