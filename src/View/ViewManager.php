<?php

namespace Michaeljennings\Carpenter\View;

use Michaeljennings\Carpenter\Nexus\Manager;

class ViewManager extends Manager
{
    /**
     * Create the illuminate view driver.
     *
     * @return Illuminate
     */
    public function createIlluminateDriver()
    {
        return new Illuminate(app('view'));
    }

    /**
     * Create the codeigniter view driver.
     *
     * @return Codeigniter
     */
    public function createCodeigniterDriver()
    {
        return new Codeigniter();
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
        return $this->config['driver'];
    }
}