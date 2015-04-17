<?php namespace Michaeljennings\Carpenter\Store; 

use Michaeljennings\Carpenter\Manager;

class StoreManager extends Manager {

    /**
     * Create the eloquent store driver.
     *
     * @return EloquentDriver
     */
    public function createEloquentDriver()
    {
        return new EloquentStore();
    }

    /**
     * Create the array store driver.
     *
     * @return ArrayStore
     */
    public function createArrayDriver()
    {
        return new ArrayStore();
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