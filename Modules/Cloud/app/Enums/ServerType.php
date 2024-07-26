<?php

namespace Modules\Cloud\Enums;

enum ServerType: string {
    case Webserver = "webserver";

    public static function allForSelect(): array {
        $types = self::cases();
        $data = [];
        foreach($types as $type) {
            $data[] = [
                "name" => $type->displayName(),
                "value" => $type->value
            ];
        }

        return $data;
    }

    public function displayName(): string {
        return match($this) {
            self::Webserver => "Webserver"
        };
    }
}
