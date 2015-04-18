<?php namespace Michaeljennings\Carpenter\Pagination;

use Michaeljennings\Carpenter\Manager;
use Michaeljennings\Carpenter\View\ViewManager;

class PaginationManager extends Manager {

    /**
     * An instance of the carpenter view manager.
     *
     * @var ViewManager
     */
    protected $view;

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