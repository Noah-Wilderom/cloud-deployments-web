<?php

use Illuminate\Support\Facades\Route;
use Modules\Services\Http\Controllers\CustomerController;
use Modules\Services\Http\Controllers\DomainController;
use Modules\Services\Http\Controllers\ServicePlanController;
use Modules\Services\Http\Controllers\ServicesController;
use Modules\Services\Http\Controllers\SubscriptionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/customers", [CustomerController::class, "index"])->name("customers.index");
Route::get("/customers/{customer}", [CustomerController::class, "show"])->name("customers.show");
Route::post("/customers", [CustomerController::class, "store"])->name("customers.store");

Route::get("/domains", [DomainController::class, "index"])->name("domains.index");
Route::get("/domains/{domain}", [DomainController::class, "show"])->name("domains.show");
Route::post("/domains", [DomainController::class, "store"])->name("domains.store");

Route::get("/subscriptions", [SubscriptionController::class, "index"])->name("subscriptions.index");
Route::post("/subscriptions", [SubscriptionController::class, "store"])->name("subscriptions.store");

Route::get("/service-plans", [ServicePlanController::class, "index"])->name("service-plans.index");
Route::get("/service-plans/{servicePlan}", [ServicePlanController::class, "show"])->name("service-plans.show");
Route::post("/service-plans", [ServicePlanController::class, "store"])->name("service-plans.store");
