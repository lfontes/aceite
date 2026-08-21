<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }

        foreach (glob(resource_path('views/vendor/jetstream/components/*.blade.php')) as $file) {
            $name = basename($file, '.blade.php');
            \Illuminate\Support\Facades\Blade::component('vendor.jetstream.components.' . $name, 'jet-' . $name);
            \Illuminate\Support\Facades\Blade::component('vendor.jetstream.components.' . $name, $name);
        }
    }
}
