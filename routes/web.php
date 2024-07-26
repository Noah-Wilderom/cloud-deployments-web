<?php

use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Webhooks\IntegrationController;
use App\Http\Middleware\EnsureSafePassword;
use App\Http\Middleware\HasTwoFactor;
use Illuminate\Support\Facades\Route;

Route::middleware(["web", "auth", "verified"])->group(function () {
    Route::get("/auth/user/change-password", [\App\Http\Controllers\Auth\PasswordController::class, "view"])->name("change-password");
    Route::post("/auth/user/change-password", [\App\Http\Controllers\Auth\PasswordController::class, "update"]);

    Route::middleware(EnsureSafePassword::class)
        ->get("/auth/user/two-factor-authentication", [TwoFactorController::class, "view"])->name("two-factor.setup");
});

Route::middleware(["web", "auth", "verified", HasTwoFactor::class, EnsureSafePassword::class])->group(function() {
    Route::get('/', [DashboardController::class, "index"])->name("dashboard");
    Route::post('/locale', [DashboardController::class, "changeLocale"])->name("change-locale");
});

Route::get("/callback/github/{userId}", [IntegrationController::class, "githubCallback"])->name("callbacks.github");
Route::post("/webhooks/github", [IntegrationController::class, "githubWebhook"])->name("webhooks.github");
Route::post("/connect/hetzner", [IntegrationController::class, "hetzner"])->name("hetzner.connect");
Route::post("/connect/cloudflare", [IntegrationController::class, "cloudflare"])->name("cloudflare.connect");
