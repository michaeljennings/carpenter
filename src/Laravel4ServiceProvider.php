<?php

namespace Michaeljennings\Carpenter;

class Laravel4ServiceProvider extends CarpenterServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     * @codeCoverageIgnore
     */
    public function boot()
    {
        $this->package('michaeljennings/carpenter', 'carpenter', realpath(__DIR__ . '/../'));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('michaeljennings.carpenter', function ($app) {
            return new Carpenter([
                'store' => $app['config']['carpenter::store'],
                'paginator' => $app['config']['carpenter::paginator'],
                'session' => $app['config']['carpenter::session'],
                'view' => $app['config']['carpenter::view'],
            ]);
        });

        $this->app->alias('michaeljennings.carpenter', 'Michaeljennings\Carpenter\Contracts\Carpenter');
    }
}