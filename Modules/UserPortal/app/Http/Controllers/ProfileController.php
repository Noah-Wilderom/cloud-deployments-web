<?php

namespace Modules\UserPortal\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Enums\IntegrationProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Response as InertiaResponse;

class ProfileController extends Controller
{
    use PasswordValidationRules;

    public function index(Request $request): InertiaResponse {
        return inertia("UserPortal::Profile", [
            "integrations" => IntegrationProvider::getUserIntegrations()->map(fn(IntegrationProvider $provider) => [
                "logo" => $provider->logoUrl(),
                "has_access" => $provider->hasAccess($request->user()),
                "is_connected" => $request->user()->hasIntegration($provider),
                "name" => $provider->displayName(),
                "description" => $provider->description(),
                "auth_url" => $provider->getIntegrationService($request->user())?->getAuthUrl(),
                "connect_url" => $provider->getIntegrationService($request->user())?->connectUrl(),
            ])
        ]);
    }

    public function update(Request $request): RedirectResponse {
        $request->validate([
            "name" => ["required", "string", "max:255"],
        ]);

        $request->user()->update([
            'name' => $request->get('name'),
        ]);

        return back()->with("notification", [
            "type" => "success",
            "title" => "Profile updated",
            "description" => "Je profiel is zojuist geupdate",
        ]);
    }

    public function changePassword(Request $request): RedirectResponse {
        $request->validate([
            'current_password' => 'current_password:web',
            'password' => $this->passwordRules($request->user(), confirm: true),
        ]);

        return back()->with("notification", [
            "type" => "success",
            "title" => "Password updated",
            "description" => "Je wachtwoord is zojuist geupdate",
        ]);
    }
}
