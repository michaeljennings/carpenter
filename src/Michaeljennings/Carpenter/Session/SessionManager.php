<?php namespace Michaeljennings\Carpenter\Session;

use Illuminate\Support\Manager;

class SessionManager extends Manager {

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
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['michaeljennings/carpenter::session.driver'];
    }

} 