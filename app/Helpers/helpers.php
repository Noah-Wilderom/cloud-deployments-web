<?php

if (! function_exists("uuidv7")) {
    function uuidV7(): string {
        // random bytes
        $value = random_bytes(16);

        // current timestamp in ms
        $timestamp = intval(microtime(true) * 1000);

        // timestamp
        $value[0] = chr(($timestamp >> 40) & 0xFF);
        $value[1] = chr(($timestamp >> 32) & 0xFF);
        $value[2] = chr(($timestamp >> 24) & 0xFF);
        $value[3] = chr(($timestamp >> 16) & 0xFF);
        $value[4] = chr(($timestamp >> 8) & 0xFF);
        $value[5] = chr($timestamp & 0xFF);

        // version and variant
        $value[6] = chr((ord($value[6]) & 0x0F) | 0x70);
        $value[8] = chr((ord($value[8]) & 0x3F) | 0x80);

        return bin2hex($value);
    }
}

if (! function_exists("verifyUuidV7")) {
    function verifyUuidV7($uuid): bool {
        // Add hyphens if missing
        if (!strpos($uuid, '-')) {
            $uuid = substr($uuid, 0, 8) . '-' .
                substr($uuid, 8, 4) . '-' .
                substr($uuid, 12, 4) . '-' .
                substr($uuid, 16, 4) . '-' .
                substr($uuid, 20, 12);
        }

        // Check if the UUID matches the general UUID structure
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[7][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', $uuid)) {
            return false;
        }

        // Split the UUID into its components
        $parts = explode('-', $uuid);
        if (count($parts) != 5) {
            return false;
        }

        // Verify the version and variant bits
        $time_high_and_version = hexdec($parts[2]);
        $clock_seq_high_and_reserved = hexdec(substr($parts[3], 0, 2));

        // Version should be 7
        $version = ($time_high_and_version & 0xF000) >> 12;
        if ($version != 7) {
            return false;
        }

        // Variant should be 10xx
        $variant = ($clock_seq_high_and_reserved & 0xC0) >> 6;
        if ($variant != 2) {
            return false;
        }

        return true;
    }
}

if (! function_exists("generate_random_name")) {
    function generate_random_name(): string {
        $generator = new \Nubs\RandomNameGenerator\Alliteration();
        $name = str_replace(" ", "-", $generator->getName());
        return strtolower($name);
    }
}
