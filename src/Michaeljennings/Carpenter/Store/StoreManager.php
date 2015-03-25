<?php namespace Michaeljennings\Carpenter\Store; 

use Illuminate\Support\Manager;

class StoreManager extends Manager {

    /**
     * Create the eloquent database driver
     *
     * @return EloquentDriver
     */
    public function createEloquentDriver()
    {
        return new EloquentStore();
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['carpenter.store.driver'];
    }

}