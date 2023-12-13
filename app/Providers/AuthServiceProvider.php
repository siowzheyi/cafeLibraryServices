<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Auth;
use App\Providers\TokenToUserProvider;
use App\Extensions\AccessTokenGuard;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        //
        $this->registerPolicies();
        Auth::extend('access_token', function ($app, $name, array $config) {
            // automatically build the DI, put it as reference
            $userProvider = app(TokenToUserProvider::class);
            $request = app('request');

            return new AccessTokenGuard($userProvider, $request, $config);
        });

        // if (! $this->app->routesAreCached()) {
        // Passport::routes();
        // }
        Passport::tokensExpireIn(now()->addMonths(1));
        Passport::refreshTokensExpireIn(now()->addMonths(2));
        Passport::personalAccessTokensExpireIn(now()->addMonths(1));

    }
}
