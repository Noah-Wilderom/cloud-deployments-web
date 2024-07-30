<?php

use App\Http\Middleware\InternalApiOnly;
use Illuminate\Support\Facades\Route;
use Modules\Services\Http\Controllers\Api;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(InternalApiOnly::class)->group(function() {
    Route::get("/service-plans/{servicePlan}/services", [Api\ServicePlanController::class, "services"])->name("service-plan.services");
});
