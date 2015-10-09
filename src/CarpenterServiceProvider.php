<?php

namespace Michaeljennings\Carpenter;

use Illuminate\Support\ServiceProvider;

class CarpenterServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../views/', 'michaeljennings/carpenter');
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('carpenter.php'),
            __DIR__ . '/../public/' => public_path(),
        ]);
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'carpenter');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('michaeljennings.carpenter', function ($app) {
            return new Carpenter($this->app['config']['carpenter']);
        });

        $this->app->alias('michaeljennings.carpenter', 'Michaeljennings\Carpenter\Contracts\Carpenter');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['michaeljennings.carpenter', 'Michaeljennings\Carpenter\Contracts\Carpenter'];
    }
}