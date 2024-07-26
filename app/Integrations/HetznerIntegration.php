<?php

namespace App\Integrations;

use App\Enums\IntegrationProvider;
use App\Models\UserIntegration;
use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use LKDev\HetznerCloud\HetznerAPIClient;

class HetznerIntegration extends Integration {

    public function connectUrl(): ?string {
        return route("hetzner.connect");
    }

    public function setToken(string $token): ?UserIntegration {
        return UserIntegration::create([
            "user_id" => $this->user->getKey(),
            "provider" => IntegrationProvider::Hetzner,
            "token" => $token,
            "data" => []
        ]);
    }
    public function verifyToken(string $token): bool {
        try {
            $hetznerClient = new HetznerAPIClient($token);
            $hetznerClient->servers()->all();
            return true;
        } catch (\Exception) {
            return false;
        }
    }

    public function service(): ?object
    {
        $token = $this->user->getIntegration(IntegrationProvider::Hetzner)?->token;
        if ($token === null) {
            return null;
        }

        return new HetznerAPIClient($token);
    }
}
