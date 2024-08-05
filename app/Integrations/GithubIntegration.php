<?php

namespace App\Integrations;

use App\Enums\IntegrationProvider;
use App\Models\UserIntegration;
use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class GithubIntegration extends Integration implements OauthIntegration {
    public function authUrl(): string {
        $state = md5($this->user->getKey());
        $encryptedUserId = Crypt::encryptString($this->user->getKey());

        $params = [
            "client_id" => config("github.client_id"),
            "scope" => "read:user,user:email,repo",
            "redirect_uri" => route("callbacks.github", ["userId" => $encryptedUserId]),
            "state" => $state
        ];
        return sprintf("https://github.com/login/oauth/authorize?%s", http_build_query($params));
    }

    public function handle(Request $request): bool
    {
        $response = Http::acceptJson()->post("https://github.com/login/oauth/access_token", [
            "client_id" => config("github.client_id"),
            "client_secret" => config("github.client_secret"),
            "code" => $request->get("code"),
        ]);

        UserIntegration::create([
            "user_id" => $this->user->getKey(),
            "provider" => IntegrationProvider::Github,
            "token" => $response->json("access_token"),
            "data" => [
                "scope" => $request->json("scope"),
            ]
        ]);

        return true;
    }

    public function service(): ?object
    {
        $token = $this->user->getIntegration(IntegrationProvider::Github)?->token;
        if ($token === null) {
            return null;
        }
        Config::set("github.connections.main.token", $token);
        return GitHub::connection();
    }
}
