<?php

namespace Michaeljennings\Carpenter\Nexus;

use Closure;
use Michaeljennings\Carpenter\Exceptions\DriverNotFoundException;

/**
 * Class Manager
 *
 * Base on the laravel support manager. Needed the power of the manager class
 * without the reliance on the laravel foundation class.
 *
 * @package Michaeljennings\Carpenter
 */
abstract class Manager
{

    /**
     * The array of created "drivers".
     *
     * @var array
     */
    protected $drivers = [];

    /**
     * The selected driver.
     *
     * @var mixed
     */
    protected $driver;

    /**
     * The carpenter config.
     *
     * @var array
     */
    protected $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    abstract public function getDefaultDriver();

    /**
     * Get a driver instance.
     *
     * @param  string $driver
     * @return mixed
     */
    public function driver($driver = null)
    {
        if ( ! isset($this->driver) || ! is_null($driver)) {
            $driver = $driver ?: $this->getDefaultDriver();

            if ( ! isset($this->drivers[$driver])) {
                $this->drivers[$driver] = $this->constructDriver($driver);
            }

            return $this->driver = $this->drivers[$driver];
        }

        return $this->driver;
    }

    /**
     * Allows you to add a custom driver to the manager. Simply pass a key
     * to retrieve the driver by and then either a class to use for the
     * driver, or a closure to be run to create the driver.
     *
     * @param string         $name
     * @param Closure|string $extension
     * @return mixed
     */
    public function extend($name, $extension)
    {
        if ($extension instanceof Closure) {
            $this->drivers[$name] = $extension();
        } else {
            $this->drivers[$name] = new $extension;
        }

        return $this->drivers[$name];
    }

    /**
     * Create a new driver instance.
     *
     * @param  string $driver
     * @return mixed
     *
     * @throws \Michaeljennings\Carpenter\Exceptions\DriverNotFoundException
     */
    protected function constructDriver($driver)
    {
        $method = 'create' . ucfirst($driver) . 'Driver';

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new DriverNotFoundException("No driver found with the name '{$driver}'");
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->driver(), $method], $parameters);
    }
}