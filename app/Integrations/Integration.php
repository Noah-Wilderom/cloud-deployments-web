<?php

namespace App\Integrations;

use App\Enums\IntegrationProvider;
use App\Models\User;
use App\Models\UserIntegration;
use Illuminate\Http\Request;
use LKDev\HetznerCloud\HetznerAPIClient;

abstract class Integration {

    protected User $user;
    public function __construct(User $user) {
        $this->user = $user;
    }

    public abstract function service(): ?object;

    public function connectUrl(): ?string {
        return null;
    }

    public function getAuthUrl(): ?string {
        if ($this instanceof OauthIntegration) {
            return $this->authUrl();
        }

        return null;
    }
    public function callback(Request $request): bool {
        if ($this instanceof OauthIntegration) {
            return $this->handle($request);
        }

        return false;
    }

    public function setToken(string $token): ?UserIntegration {
        return null;
    }
    public function verifyToken(string $token): bool {
        return false;
    }

}
