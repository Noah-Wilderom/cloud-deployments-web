<?php

namespace App\Services;

use Exception;

class Version {

    private const VERSION_PATTERN = "/v(\d+)\.(\d+)\.(\d+)(?:-(\w+))?/";
    private array $config;

    private ?string $version = null;
    private ?string $major = null;
    private ?string $minor = null;
    private ?string $patch = null;
    private ?string $prerelease = null;

    public function __construct(array $config = []) {
        $this->config = $config;
        $this->version = $config["version"] ?? null;

        if (is_null($this->version)) {
            throw new Exception("Cannot find version");
        }

        $this->parse();
    }
    private function parse(): void {
        // Define regex pattern to match version components
        // Use preg_match to parse version components
        if (preg_match(self::VERSION_PATTERN, $this->version, $matches)) {
            $this->major = (int)$matches[1];
            $this->minor = (int)$matches[2];
            $this->patch = (int)$matches[3];
            $this->prerelease = isset($matches[4]) ? $matches[4] : null;
        } else {
            throw new Exception("Invalid version format");
        }
    }

    public function fullVersion(): string {
        return $this->version;
    }

    public function major(): string {
        return $this->major;
    }

    public function minor(): string {
        return $this->minor;
    }

    public function patch(): string {
        return $this->patch;
    }

    public function prerelease(): ?string {
        return $this->prerelease;
    }
}
