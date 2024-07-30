<?php

namespace Modules\Services\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Inertia\Response as InertiaResponse;
use Modules\Services\Http\Requests\StoreSubscriptionRequest;
use Modules\Services\Models\Customer;
use Modules\Services\Models\ServicePlan;
use Modules\Services\Models\Subscription;

class SubscriptionController extends Controller
{
    public function index(): InertiaResponse {
        return inertia("Services::Subscriptions/Index", [
            "subscriptions" => Subscription::with(["customer", "service", "servicePlan"])->get(),
            "customers" => Customer::all(),
            "plans" => ServicePlan::all(),
        ]);
    }

    public function store(StoreSubscriptionRequest $request): RedirectResponse {
        $servicePlan = ServicePlan::find($request->get("service_plan_id"));
        $subscription = Subscription::create([
            "user_id" => $request->user()->getKey(),
            "customer_id" => $request->get("customer_id"),
            "service_plan_id" => $servicePlan->getKey(),
            "service_type" => $servicePlan->service_type,
            "service_id" => $request->get("service_id"),
            "name" => $request->get("name"),
            "price" => $request->get("price") ?? $servicePlan->base_price,
        ]);

        return back();
    }
}
