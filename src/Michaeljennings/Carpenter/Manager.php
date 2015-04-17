<?php namespace Michaeljennings\Carpenter;

use Michaeljennings\Carpenter\Exceptions\DriverNotFoundException;

/**
 * Class Manager
 *
 * Base on the laravel support manager. Needed the power of the manager class
 * without the reliance on the laravel foundation class.
 *
 * @package Michaeljennings\Carpenter
 */
abstract class Manager {

    /**
     * The array of created "drivers".
     *
     * @var array
     */
    protected $drivers = array();

    /**
     * The carpenter config.
     *
     * @var array
     */
    protected $config = array();

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
     * @param  string  $driver
     * @return mixed
     */
    public function driver($driver = null)
    {
        $driver = $driver ?: $this->getDefaultDriver();

        if ( ! isset($this->drivers[$driver])) {
            $this->drivers[$driver] = $this->constructDriver($driver);
        }

        return $this->drivers[$driver];
    }

    /**
     * Create a new driver instance.
     *
     * @param  string  $driver
     * @return mixed
     *
     * @throws \Michaeljennings\Carpenter\Exceptions\DriverNotFoundException
     */
    protected function constructDriver($driver)
    {
        $method = 'create'.ucfirst($driver).'Driver';

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new DriverNotFoundException("No driver found with the name '{$driver}'");
    }

    public function getConfig()
    {

    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->driver(), $method), $parameters);
    }

}