<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class CloudflareClient {

    private PendingRequest $client;

    public function __construct(string $token) {
        $this->client = Http::asJson()
            ->acceptJson()
            ->withHeader("Authorization", sprintf("Bearer %s", $token))
            ->baseUrl("https://api.cloudflare.com/client/v4");
    }

    public function verify(): bool {
        $resp = $this->client->get("/user/tokens/verify");
        return $resp->status() === 200;
    }

    /**
     * @return \Illuminate\Support\Collection
     * @throws \Illuminate\Http\Client\ConnectionException
     * @link https://developers.cloudflare.com/api/operations/zones-get
     */
    public function zonesList(array $options = []): Collection  {
        $resp = $this->client->get("/zones", $options);
        return $resp->collect();
    }
}
