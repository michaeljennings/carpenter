<?php

namespace Michaeljennings\Carpenter\Store;

use Michaeljennings\Carpenter\Nexus\Manager;

class StoreManager extends Manager
{
    /**
     * Create the eloquent store driver.
     *
     * @return Eloquent
     */
    public function createEloquentDriver()
    {
        return new Eloquent();
    }

    /**
     * Create the eloquent store driver.
     *
     * @return Laravel4\Eloquent
     */
    public function createLaravel4Driver()
    {
        return new Laravel4\Eloquent(app());
    }

    /**
     * Create the illuminate store driver.
     *
     * @return Illuminate
     */
    public function createIlluminateDriver()
    {
        return new Illuminate(app('db'));
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
     * @return Codeigniter
     */
    public function createCodeigniterDriver()
    {
        return new Codeigniter();
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->config['driver'];
    }
}