<?php
namespace Cedar;

use Illuminate\Support\ServiceProvider;

class CedarServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('cedar.php')
        ], 'config');

        \App::bind('cedar', function () {
            return new Cedar;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}