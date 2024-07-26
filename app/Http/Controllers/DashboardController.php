<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Response as InertiaResponse;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index(Request $request): InertiaResponse
    {
        return inertia("Dashboard/Index", []);
    }

    public function changeLocale(Request $request) {
        $lang = $request->get("language");
        $request->user()->update(["locale" => $lang]);
        App::setLocale($lang);
        return back()
            ->with("notification", [
                "type" => "success",
                "title" => "Language changed",
                "description" => sprintf("You changed the language to: %s", trans(sprintf("pages.header.language_%s", $lang)))
            ]);
    }
}
