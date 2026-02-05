<?php

namespace App\Providers;

use App\Models\Actas;
use App\Observers\ActasObserver;
use Illuminate\Support\Facades\Gate;
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
        \App\Models\Post::observe(\App\Observers\PostObserver::class);
        Gate::define('admin', function ($user) {
            return $user->is_admin;
        });

        Gate::define('author', function ($user, $post) {
            return $user->id === $post->user_id;
        });

        Gate::after(function ($user, $ability) {
            return $user->hasRole('Admin') ? true : null;
        });

        //Observer para eliminar foto de acta adjunta de credencial,
        Actas::observe(ActasObserver::class);
    }
}
