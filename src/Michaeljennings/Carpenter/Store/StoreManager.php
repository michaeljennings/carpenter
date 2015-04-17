<?php namespace Michaeljennings\Carpenter\Store; 

use Michaeljennings\Carpenter\Manager;

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
        return $this->config['store']['driver'];
    }

}