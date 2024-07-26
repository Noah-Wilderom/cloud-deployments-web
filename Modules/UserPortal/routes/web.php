<?php

use Illuminate\Support\Facades\Route;
use Modules\UserPortal\Http\Controllers\ProfileController;
use Modules\UserPortal\Http\Controllers\SubscriptionController;
use Modules\UserPortal\Http\Controllers\SupportController;

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


Route::get("/profile", [ProfileController::class, "index"])->name("profile.index");
Route::post("/profile", [ProfileController::class, "update"])->name("profile.update");
Route::post("/profile/change-password", [ProfileController::class, "changePassword"])->name("profile.change-password");

Route::get("/subscription", [SubscriptionController::class, "index"])->name("subscription.index");
Route::get("/support", [SupportController::class, "index"])->name("support.index");
