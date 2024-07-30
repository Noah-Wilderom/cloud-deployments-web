<?php

namespace Modules\Services\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controller;
use Modules\Services\Models\ServicePlan;

class ServicePlanController extends Controller
{
    public function services(ServicePlan $servicePlan): Collection {
        $serviceType = $servicePlan->service_type;
        $service = new $serviceType;

        return $service::all();
    }
}
