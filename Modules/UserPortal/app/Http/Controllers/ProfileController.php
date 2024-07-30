<?php

namespace Modules\UserPortal\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\Enums\IntegrationProvider;
use App\Models\UserKey;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Inertia\Response as InertiaResponse;

class ProfileController extends Controller
{
    use PasswordValidationRules;

    public function index(Request $request): InertiaResponse {
        $user = $request->user();
        $user->loadMissing(["keys"]);

        return inertia("UserPortal::Profile", [
            "integrations" => IntegrationProvider::getUserIntegrations()->map(fn(IntegrationProvider $provider) => [
                "logo" => $provider->logoUrl(),
                "has_access" => $provider->hasAccess($request->user()),
                "is_connected" => $request->user()->hasIntegration($provider),
                "name" => $provider->displayName(),
                "description" => $provider->description(),
                "auth_url" => $provider->getIntegrationService($request->user())?->getAuthUrl(),
                "connect_url" => $provider->getIntegrationService($request->user())?->connectUrl(),
            ]),
            "sshKeys" => $request->user()->keys,
        ]);
    }

    public function addSshKey(Request $request): RedirectResponse {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "public_key" => ["required", "string"]
        ]);

        $signaturePieces = explode("= ", $request->get("public_key"));
        $dir = "user/" . uuidV7();

        $publicKey = Crypt::encryptString($request->get("public_key"));
        Storage::disk("keypairs")->put($dir, $publicKey);

        $request->user()->keys()->create([
            "name" => $request->get("name"),
            "path" => $dir,
            "signature" => $signaturePieces[1] ?? null,
//            "primary" => $request->user()->keys()->count() === 0,
        ]);

        return back()
            ->with("notifications", [
                "type" => "success",
                "title" => "SSH Key added",
                "description" => "SSH Key added",
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
