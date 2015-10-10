<?php

namespace Michaeljennings\Carpenter\Session;

use Michaeljennings\Carpenter\Nexus\Manager;

class SessionManager extends Manager
{
    /**
     * Return a new instance of the native php session driver.
     *
     * @return Native
     */
    public function createNativeDriver()
    {
        return new Native($this->config);
    }

    /**
     * Return a new instance of the illuminate driver.
     *
     * @return Illuminate
     */
    public function createIlluminateDriver()
    {
        return new Illuminate(app('session')->driver());
    }

    /**
     * Return a new instance of the codeigniter driver.
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