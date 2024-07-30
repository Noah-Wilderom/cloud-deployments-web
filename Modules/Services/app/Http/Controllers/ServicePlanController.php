<?php

namespace Modules\Services\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Inertia\Response as InertiaResponse;
use Modules\Cloud\Models\Project;
use Modules\Cloud\Models\Server;
use Modules\Services\Data\ServicePlanResourcesData;
use Modules\Services\Data\ServicePlanSettingsData;
use Modules\Services\Http\Requests\StoreServicePlanRequest;
use Modules\Services\Models\Domain;
use Modules\Services\Models\ServicePlan;

class ServicePlanController extends Controller
{
    public function index(): InertiaResponse {
        return inertia("Services::ServicePlans/Index", [
            "plans" => ServicePlan::all(),
            "service_types" => ServicePlan::getServiceTypes(),
        ]);
    }

    public function store(StoreServicePlanRequest $request): RedirectResponse {
        $servicePlan = ServicePlan::create([
            "user_id" => $request->user()->getKey(),
            "service_type" => $request->get("service_type"),
            "name" => $request->get("name"),
            "base_price" => (int) $request->get("base_price") * 100,
            "resources" => new ServicePlanResourcesData(),
            "settings" => new ServicePlanSettingsData(),
        ]);

        return back()
            ->with("notifications", [
                "type" => "success",
                "title" => "Service Plan Created",
                "description" => sprintf("Service Plan %s Created", $servicePlan->name),
            ]);
    }
}
