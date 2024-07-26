<?php

namespace App\Http\Controllers\Webhooks;

use App\Enums\IntegrationProvider;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class IntegrationController extends Controller
{
    public function githubCallback(Request $request, string $userId) {
        $userId = Crypt::decryptString($userId);
        $user = User::query()->findOrFail($userId);
        if($request->get("state") !== md5($userId)) {
            abort(500, ["message" => "State invalid"]);
        }

        $ok = IntegrationProvider::Github->getIntegrationService($user)
            ->callback($request);

        $notification = $ok
            ? ["type" => "success", "title" => "Integration is now connected", "description" => "Github has been successfully connected"]
            : ["type" => "error", "title" => "Integration has failed", "description" => "Github integration has failed" ];

        return redirect()
            ->route("userportal::profile.index", ["tab" => "integrations"])
            ->with("notifications", $notification);
    }

    public function hetzner(Request $request) {
        $request->validate([
            "token" => ["required", "string", "max:255"]
        ]);

        $service = IntegrationProvider::Hetzner->getIntegrationService($request->user());

        if(! $service->verifyToken($request->get("token"))) {
            return back()
                ->withErrors(["token" => "The token is invalid."]);
        }

        $service->setToken($request->get("token"));

        return back()
            ->with("notifications", [
                "type" => "success",
                "title" => "Integration is now connected",
                "description" => "Hetzner Integration has been successfully connected",
            ]);
    }

    public function cloudflare(Request $request) {
        $request->validate([
            "token" => ["required", "string", "max:255"]
        ]);

        $service = IntegrationProvider::Cloudflare->getIntegrationService($request->user());

        if(! $service->verifyToken($request->get("token"))) {
            return back()
                ->withErrors(["token" => "The token is invalid."]);
        }

        $service->setToken($request->get("token"));

        return back()
            ->with("notifications", [
                "type" => "success",
                "title" => "Integration is now connected",
                "description" => "Cloudflare Integration has been successfully connected",
            ]);
    }
}
