<?php

namespace Modules\Cloud\Data;

use App\Enums\IntegrationProvider;
use App\Models\User;
use Github\Client;
use Github\Exception\RuntimeException;
use Spatie\LaravelData\Data;

class GitRepositoryData extends Data
{
    public function __construct(
        public string $id,
        public string $sshCloneUrl,
        public string $name,
        public string $username,
        public string $repository,
        public string $url,
    ) {}

    public static function newFromGithub(User $user, string $githubId): self|false {

        try {
            $client = IntegrationProvider::Github->getIntegrationService($user)
                ->service();
            $response = $client->repositories()->showById($githubId);
        } catch (RuntimeException $e) {
            if ($e->getMessage() !== "Not Found") {
                throw $e;
            }

            return false;
        }

        return new self(
            id: $githubId,
            sshCloneUrl: $response["ssh_url"],
            name: $response["full_name"],
            username: explode("/", $response["full_name"])[0],
            repository: $response["name"],
            url: $response["html_url"],
        );
    }
}
