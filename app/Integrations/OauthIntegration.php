<?php

namespace App\Integrations;
use App\Models\User;
use Illuminate\Http\Request;
interface OauthIntegration {
    public function authUrl(): string;
    public function handle(Request $request): bool;
}
