<?php

namespace Modules\Cloud\Enums;

enum ProjectEnvironment: string {

    case Development = "development";
    case Staging = "staging";
    case Production = "production";

    public function displayName(): string {
        return match($this) {
            self::Development => "Development",
            self::Staging => "Staging",
            self::Production => "Production",
        };
    }

    /**
     * @return array<ProjectEnvironment>
     */
    public static function defaultEnvironments(): array {
        return [
            self::Production,
        ];
    }
}
