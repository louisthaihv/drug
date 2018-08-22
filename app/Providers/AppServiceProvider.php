<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('admin', function ($user) {
            return ($user->role->name === 'admin');
        });

        Gate::define('team-member', function ($user, $team) {
            return ($user->teams->find($team->id));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
