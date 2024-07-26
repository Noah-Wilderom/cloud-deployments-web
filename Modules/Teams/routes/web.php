<?php

use Illuminate\Support\Facades\Route;
use Modules\Teams\Http\Controllers\DeliveryController;
use Modules\Teams\Http\Controllers\RoleController;
use Modules\Teams\Http\Controllers\UserController;

Route::get("/roles", [RoleController::class, "index"])->name("roles.index");
Route::get("/roles/{role}", [RoleController::class, "show"])->name("roles.show");
Route::post("/roles/{role}", [RoleController::class, "update"])->name("roles.update");

Route::get("/users", [UserController::class, "index"])->name("users.index");
Route::get("/users/{user}", [UserController::class, "show"])->name("users.show");
Route::get("/users/{user}/logs", [UserController::class, "getUserLogs"])->name("users.fetch-activity");

