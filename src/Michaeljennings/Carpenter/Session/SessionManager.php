<?php namespace Michaeljennings\Carpenter\Session;

use Illuminate\Support\Manager;

class SessionManager extends Manager {

    /**
     * Return a new instance of the native php session driver.
     * 
     * @return Michaeljennings\Carpenter\Session\NativeDriver
     */
    public function createNativeDriver()
    {
        return new NativeDriver();
    }

    /**
     * Return a new instance of the illuminate driver.
     *
     * @return Michaeljennings\Carpenter\Session\IlluminateDriver
     */
    public function createIlluminateDriver()
    {
        return new IlluminateDriver($this->app['session']);
    }

    /**
     * Return a new instance of the codeigniter driver.
     * 
     * @return \Michaeljennings\Carpenter\Session\CodeigniterDriver
     */
    public function createCodeigniterDriver()
    {
        return new CodeigntierDriver();
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['carpenter.session.driver'];
    }

} 