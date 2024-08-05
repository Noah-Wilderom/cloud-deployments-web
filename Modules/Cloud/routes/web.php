<?php

use Illuminate\Support\Facades\Route;
use Modules\Cloud\Http\Controllers\BackupController;
use Modules\Cloud\Http\Controllers\CloudController;
use Modules\Cloud\Http\Controllers\ProjectController;
use Modules\Cloud\Http\Controllers\ScriptController;
use Modules\Cloud\Http\Controllers\ServerController;

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

Route::get("/servers", [ServerController::class, "index"])->name("servers.index");
Route::get("/servers/{server}", [ServerController::class, "show"])->name("servers.show");
Route::post("/servers", [ServerController::class, "store"])->name("servers.store");

Route::get("/projects", [ProjectController::class, "index"])->name("projects.index");
Route::post("/projects", [ProjectController::class, "store"])->name("projects.store");

Route::get("/scripts", [ScriptController::class, "index"])->name("scripts.index");
Route::get("/backups", [BackupController::class, "index"])->name("backups.index");
