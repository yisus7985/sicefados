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
        // Registrar el módulo AVICONTROL
        $this->app->register(\Modules\AVICONTROL\Providers\AVICONTROLServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Cargar el módulo AVICONTROL
        $this->app->register(\Modules\AVICONTROL\Providers\AVICONTROLServiceProvider::class);
    }
}
