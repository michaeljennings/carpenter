<?php namespace Michaeljennings\Carpenter\Pagination;

use Michaeljennings\Carpenter\Manager;

class PaginationManager extends Manager {

    /**
     * Create the illuminate pagination driver.
     *
     * @return IlluminateDriver
     */
    public function createIlluminateDriver()
    {
        return new IlluminateDriver(app());
    }

    /**
     * Create the native pagination driver.
     *
     * @return Native
     */
    public function createNativeDriver()
    {
        return new Native();
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->config['paginator']['driver'];
    }

} 