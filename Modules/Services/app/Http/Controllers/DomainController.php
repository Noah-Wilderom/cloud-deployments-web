<?php

namespace Modules\Services\Http\Controllers;

use App\Enums\IntegrationProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Response as InertiaResponse;
use Modules\Services\Http\Requests\StoreCustomerRequest;
use Modules\Services\Http\Requests\StoreDomainRequest;
use Modules\Services\Models\Customer;
use Modules\Services\Models\Domain;

class DomainController extends Controller
{
    public function index(Request $request): InertiaResponse {
        //TODO: make the cloudflareDomains fast (cache?)
        return inertia("Services::Domains/Index", [
            "customers" => Customer::all(),
            "domains" => Domain::all()->loadMissing(["customer"]),
            "cloudflareDomains" => IntegrationProvider::Cloudflare->getIntegrationService($request->user())
                ->service()
                ?->zonesList()
                ->get("result")
        ]);
    }

    public function store(StoreDomainRequest $request): RedirectResponse {
        $domain = $request->user()->domains()->create($request->safe()->all() + ["verified" => false]);

        return back()->with("notifications", [
            "type" => "success",
            "title" => "Domain has been created",
            "description" => sprintf("Domain %s has been created", $domain->name),
        ]);
    }
}
