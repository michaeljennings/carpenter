<?php namespace Michaeljennings\Carpenter\View;

use Illuminate\Support\Manager;

class ViewManager extends Manager {

    /**
     * Create the illuminate view driver.
     *
     * @return IlluminateDriver
     */
    public function createIlluminateDriver()
    {
        return new IlluminateDriver($this->app['view']);
    }

    /**
     * Create the codeigniter view driver.
     * 
     * @return CodeigniterDriver
     */
    public function createCodeigniterDriver()
    {
        return new CodeigniterDriver();
    }

    /**
     * Create the native view driver.
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
        return $this->app['config']['carpenter.view.driver'];
    }

}