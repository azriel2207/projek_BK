<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Discord\Provider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // register bindings here if needed
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share authenticated user data with all views
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $name = $user->name;
                $role = $user->role;
                $avatar = $user->provider == null
                    ? url('assets/images/user/' . $user->avatar)
                    : $user->avatar;

                $view->with(compact('user', 'name', 'role', 'avatar'));
            }
        });

        // Register Socialite provider
        Event::listen(SocialiteWasCalled::class, function (SocialiteWasCalled $event) {
            $event->extendSocialite('discord', Provider::class);
        });

    }
}