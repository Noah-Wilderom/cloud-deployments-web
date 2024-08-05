<?php

namespace Modules\Cloud\Enums;

// Make this a CRUD?
enum ProjectTemplate: string {
    case Laravel = "laravel";
    case Go = "go";
    case Docker = "docker";

    public function requiredSoftwares(): array {
        return match($this) {
            self::Laravel => [
                Software::Nginx,
                Software::Php,
                Software::Composer,
                Software::MySql,
                Software::Certbot,
            ],
            self::Go => [
                Software::Nginx,
                Software::Go,
                Software::MySql,
                Software::Certbot,
            ],
            self::Docker => [
                Software::Nginx,
                Software::Docker,
                Software::Certbot,
            ],
        };
    }

    public function displayName(): string {
        return match($this) {
            self::Laravel => "Laravel",
            self::Go => "Go",
            self::Docker => "Docker",
        };
    }

    public static function getAllByDisplayName(): array {
        return collect(self::cases())->mapWithKeys(function (ProjectTemplate $template) {
            return [$template->value => $template->displayName()];
        })->toArray();
    }
}
