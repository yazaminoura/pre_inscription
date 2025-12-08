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
