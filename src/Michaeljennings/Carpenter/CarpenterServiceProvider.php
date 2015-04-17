<?php namespace Michaeljennings\Carpenter;

use Illuminate\Support\ServiceProvider;

class CarpenterServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../../views/', 'michaeljennings/carpenter');
        $this->publishes([__DIR__.'/../../config/config.php' => config_path('carpenter.php')]);
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'carpenter');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerDrivers($this->getConfig());

        $this->app->bind('michaeljennings.carpenter.driverContainer', function($app)
        {
            return new DriverContainer([
                'store' => $app['michaeljennings.carpenter.store'],
                'paginator' => $app['michaeljennings.carpenter.paginator'],
                'session' => $app['michaeljennings.carpenter.session'],
                'view' => $app['michaeljennings.carpenter.view'],
            ]);
        });

        $this->app->singleton('michaeljennings.carpenter', function($app)
        {
            return new Carpenter(
                $app['michaeljennings.carpenter.driverContainer'],
                $this->app['config']['carpenter']
            );
        });

        $this->app->bind('Michaeljennings\Carpenter\Contracts\Carpenter', function($app)
        {
            return $app['michaeljennings.carpenter'];
        });
    }

    /**
     * Register the carpenter drivers.
     *
     * @param array $config
     * @return void
     */
    private function registerDrivers(array $config)
    {
        $this->app->bind('michaeljennings.carpenter.store', function($app) use ($config)
        {
            return new Store\StoreManager($config);
        });

        $this->app->bind('michaeljennings.carpenter.paginator', function($app) use ($config)
        {
            return new Pagination\PaginationManager($config);
        });

        $this->app->bind('michaeljennings.carpenter.session', function($app) use ($config)
        {
            return new Session\SessionManager($config);
        });

        $this->app->bind('michaeljennings.carpenter.view', function($app) use ($config)
        {
            return new View\ViewManager($config);
        });
    }

    /**
     * Get the carpenter config.
     *
     * @return array
     */
    protected function getConfig()
    {
        return $this->app['config']['carpenter'];
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}
