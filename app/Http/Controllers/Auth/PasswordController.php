<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Response as InertiaResponse;

class PasswordController extends Controller
{
    use PasswordValidationRules;

    public function view(Request $request): InertiaResponse|RedirectResponse {
        if(! $request->user()->needsNewPassword()) {
            return redirect()->route("dashboard");
        }

        return inertia("Auth/ChangePassword");
    }

    public function update(Request $request) {

        $request->validate([
            "password" => $this->passwordRules($request->user()),
        ]);

        $password = Hash::make($request->get("password"));
        $request->user()->addPasswordToPreviouslyUsed($password);
        $request->user()->updateQuietly([
            "password" => $password,
            "password_changed_at" => now(),
            "temporary_password" => false,
        ]);

        return redirect()->route("dashboard")->with("notifications", [
            "type" => "success",
            "title" => "Password changed",
            "description" => "Password changed",
        ]);
    }
}
