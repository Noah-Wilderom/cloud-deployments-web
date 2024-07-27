<?php

namespace Modules\Cloud\Http\Controllers;

use App\Enums\IntegrationProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Inertia\Response as InertiaResponse;
use LKDev\HetznerCloud\HetznerAPIClient;
use LKDev\HetznerCloud\RequestOpts;
use Modules\Cloud\Data\ServerCloudProviderData;
use Modules\Cloud\Enums\ServerStatus;
use Modules\Cloud\Enums\ServerType;
use Modules\Cloud\Http\Requests\StoreServerRequest;
use Modules\Cloud\Models\Server;
use Modules\Services\Models\Customer;
use App\SSH;

class ServerController extends Controller
{

    public function index(Request $request): InertiaResponse {
        $hetznerServerTypes = IntegrationProvider::Hetzner->getIntegrationService($request->user())
            ->service()
            ->serverTypes()
            ->all();

        for($i = 0; $i < count($hetznerServerTypes); $i++) {
            foreach($hetznerServerTypes[$i]->prices as $price) {
                if($price->location === "nbg1") {
                    $hetznerServerTypes[$i]->monthly_price = $price->price_monthly->net;
                }
            }
        }

        return inertia("Cloud::Servers/Index", [
            "servers" => Server::all(),
            "customers" => Customer::all(),
            "cloudTypes" => $hetznerServerTypes,
            "serverTypes" => ServerType::allForSelect()
        ]);
    }

    public function show(Server $server): InertiaResponse {
        $server->loadMissing(["projects", "tasks"]);
        return inertia("Cloud::Servers/Show", [
            "server" => $server,
//            "initLogs" => Storage::disk("ssh-logs")->get($server->getKey()),
        ]);
    }

    public function store(StoreServerRequest $request): RedirectResponse {
        $sshKeyPairDir = uuidV7();
        $sshPrivKeyPath = sprintf("%s/id_rsa", $sshKeyPairDir);
        $sshPubKeyPath = sprintf("%s/id_rsa.pub", $sshKeyPairDir);
        (new SSH\KeyPairGenerator())->generateKeyPair($sshPrivKeyPath, $sshPubKeyPath);

        $service = IntegrationProvider::Hetzner->getIntegrationService($request->user())
            ->service();

        $serverImage = $service->images()->getByName("ubuntu-24.04", "x86");
        $serverLocation = $service->locations()->getByName("nbg1");
        $sshKey = $service->sshKeys()->create(sprintf("cloud-deployments-%s", $sshKeyPairDir), Crypt::decryptString(Storage::disk("keypairs")->get($sshPubKeyPath)));
        $serverType = $service->serverTypes()->getById($request->get("cloud_provider_type"));
        $serverResp = $service->servers()->createInLocation(
            $request->get("name"),
            $serverType,
            $serverImage,
            $serverLocation,
            [$sshKey->id],
        );

        $serverRespData = $serverResp->getResponsePart("server");
        $server = Server::create([
            "user_id" => $request->user()->id,
            "customer_id" => $request->get("customer_id"),
            "type" => $request->get("type"),
            "ssh_credentials_path" => $sshKeyPairDir,
            "status" => ServerStatus::Created,
            "name" => $request->get("name"),
            "cloud_provider" => new ServerCloudProviderData(
                id: $serverRespData->id,
                type: $request->get("cloud_provider_type"),
                iso: sprintf("%s (%s)", $serverRespData->image->name, $serverRespData->image->architecture),
                sshKeyId: $sshKey->id,
            ),
            "host" => [
                "ipv4" => $serverRespData->public_net->ipv4->ip,
                "ipv6" => $serverRespData->public_net->ipv6->ip,
            ],
        ]);

        return back()
            ->with("notifications", [
                "type" => "success",
                "title" => "Server created",
                "description" => "Server wil start to initialize in a second",
            ]);
    }
}
