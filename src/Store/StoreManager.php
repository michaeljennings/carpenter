<?php namespace Michaeljennings\Carpenter\Store; 

use Michaeljennings\Carpenter\Nexus\Manager;

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
     * Create the eloquent store driver.
     *
     * @return Laravel4\EloquentStore
     */
    public function createLaravel4Driver()
    {
        return new Laravel4\EloquentStore();
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
     * Create the codeigniter store.
     * 
     * @return CodeigniterStore
     */
    public function createCodeigniterDriver()
    {
        return new CodeigniterStore();
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