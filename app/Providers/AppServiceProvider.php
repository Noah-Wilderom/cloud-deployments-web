<?php

namespace App\Providers;

use App\Listeners\UserEventSubscriber;
use App\Models\Role;
use App\Services\Version;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
        Model::shouldBeStrict(! $this->app->isProduction());

        $this->app->singleton(Version::class, function (Application $app) {
            return new Version($this->app["config"]["version"]);
        });

        Gate::before(function ($user, $ability) {
            return $user->hasRole(Role::SUPER_ADMIN) ? true : null;
        });

        Event::subscribe(UserEventSubscriber::class);

        if(str_contains(config("app.url"), "https://")) {
            URL::forceScheme('https');
            URL::forceRootUrl(config("app.url"));
        }
    }



}
