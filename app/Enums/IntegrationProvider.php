<?php

namespace App\Enums;

use App\Features\CanConnectProvider;
use App\Integrations\CloudflareIntegration;
use App\Integrations\GithubIntegration;
use App\Integrations\HetznerIntegration;
use App\Integrations\Integration;
use App\Models\User;
use Illuminate\Support\Collection;
use Laravel\Pennant\Feature;

enum IntegrationProvider: string {
    case Github = "github";
    case Slack = "slack";
    case Cloudflare = "cloudflare";
    case Hetzner = "hetzner";
    case GoogleAnalytics = "google-analytics";

    public static function getUserIntegrations(): Collection {
        return collect([
            self::Github,
            self::Slack,
            self::Cloudflare,
            self::Hetzner,
            self::GoogleAnalytics,
        ]);
    }

    public function getIntegrationService(User $user): ?Integration {
        return match ($this) {
            self::Github => new GithubIntegration($user),
            self::Hetzner => new HetznerIntegration($user),
            self::Cloudflare => new CloudflareIntegration($user),
            default => null,
        };
    }

    public function logoUrl(): array {
        return match ($this) {
            self::Github => [
                "dark" => "/media/brand-logos/github-dark.svg",
                "light" => "/media/brand-logos/github-light.svg",
            ],
            self::Slack => [
                "dark" => "/media/brand-logos/slack.svg",
                "light" => "/media/brand-logos/slack.svg",
            ],
            self::Cloudflare => [
                "dark" => "/media/brand-logos/cloudflare.svg",
                "light" => "/media/brand-logos/cloudflare.svg",
            ],
            self::Hetzner => [
                "dark" => "/media/brand-logos/hetzner.png",
                "light" => "/media/brand-logos/hetzner.png",
            ],
            self::GoogleAnalytics => [
                "dark" => "/media/brand-logos/google-analytics-2.svg",
                "light" => "/media/brand-logos/google-analytics-2.svg",
            ],
        };
    }

    public function displayName(): string {
        return match ($this) {
            self::Github => "Github",
            self::Slack => "Slack",
            self::Cloudflare => "Cloudflare",
            self::Hetzner => "Hetzner",
            self::GoogleAnalytics => "Google Analytics",
        };
    }

    public function description(): string {
        return match ($this) {
            self::Github => "Git repository management",
            self::Slack => "Messaging app",
            self::Cloudflare => "DNS provider",
            self::Hetzner => "Cloud provider",
            self::GoogleAnalytics => "Insights into website traffic and marketing effectiveness.",
        };
    }

    public function hasAccess(User $user): bool {
//        return Feature::for($user)->for($this)
//            ->active(CanConnectProvider::class);
        return true;
    }

    public function hasIntegration(User $user): bool {
//        dd($this->name);
        return $this->hasAccess($user) && $user->hasIntegration($this);
    }
}
