<?php

namespace App\Providers;

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
   public function boot()
    {
        // Dates affichées en français (« il y a 2 jours », « 25 septembre 2026 »)
        \Carbon\Carbon::setLocale('fr');

        // Add macro to check if any of multiple fields are filled
        \Illuminate\Http\Request::macro('anyFilled', function ($keys) {
            foreach ((array) $keys as $key) {
                if ($this->filled($key)) {
                    return true;
                }
            }
            return false;
        });
    }
}
