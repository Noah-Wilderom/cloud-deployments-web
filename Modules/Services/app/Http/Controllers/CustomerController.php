<?php

namespace Modules\Services\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Inertia\Response as InertiaResponse;
use Modules\Services\Http\Requests\StoreCustomerRequest;
use Modules\Services\Models\Customer;

class CustomerController extends Controller
{
    public function index(): InertiaResponse {
        return inertia("Services::Customers/Index", [
            "teams" => auth()->user()->allTeams(),
            "customers" => Customer::forAuthUser(),
        ]);
    }

    public function show(Customer $customer): InertiaResponse {
        return inertia("Services::Customers/Show", [
            "customer" => $customer
        ]);
    }

    public function store(StoreCustomerRequest $request): RedirectResponse {
        $customer = $request->user()->customers()->create($request->safe()->all());

        return back()->with("notifications", [
            "type" => "success",
            "title" => "Customer has been created",
            "description" => sprintf("Customer %s has been created", $customer->name),
        ]);
    }
}
