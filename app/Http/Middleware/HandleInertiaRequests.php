<?php

namespace App\Http\Middleware;

use App\Services\Version;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            "appName" => config("app.name"),
            "assetPath" => asset("assets/", str_contains(config("app.url"), "https://")),
            "baseUrl" => config("app.url"),
            "version" => app(Version::class)->fullVersion(),
            'auth.user' => fn () => $request->user()
                ? $request->user()
                : null,
            "locale" => App::currentLocale(),
            "queryParams" => $request->query(),
            "flash" => [
                "notifications" => session()->has("notifications")
                    ? session()->get("notifications")
                    : null,
            ],
        ]);
    }
}
