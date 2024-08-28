<?php

namespace Modules\Cloud\Http\Controllers;

use Illuminate\Routing\Controller;
use Inertia\Response as InertiaResponse;
use Modules\Cloud\Models\Database;

class DatabaseController extends Controller
{

    public function index(): InertiaResponse {
        return inertia("Cloud::Databases/Index", [
            "databases" => Database::all(),
        ]);
    }
}
