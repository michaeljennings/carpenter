<?php namespace Michaeljennings\Carpenter\Session;

class IlluminateDriver implements SessionInterface {

    /**
     * The illuminate session driver
     *
     * @var mixed
     */
    protected $session;

    public function __construct($session)
    {
        $this->session = $session;
    }

    /**
     * Retrieve an item from the session
     *
     * @param  string $name
     * @return mixed
     */
    public function get($name)
    {
        return $this->session->get($name);
    }

    /**
     * Store an item in the session
     *
     * @param  string $name
     * @param  mixed $value
     * @return mixed
     */
    public function put($name, $value)
    {
        return $this->session->put($name, $value);
    }

    /**
     * Flash a value to the session
     *
     * @param  string $name
     * @param  mixed $value
     * @return mixed
     */
    public function flash($name, $value)
    {
        return $this->session->flash($name, $value);
    }

    /**
     * Check if a value is set in the session
     *
     * @param  string $name
     * @return mixed
     */
    public function has($name)
    {
        return $this->session->has($name);
    }

    /**
     * Remove a value from the session
     *
     * @param  string $name
     * @return mixed
     */
    public function forget($name)
    {
        return $this->session->forget($name);
    }

    /**
     * Catch any undefined methods and run them on the Illuminate session
     * driver.
     *
     * @param  string $method
     * @param  array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->session, $method], $args);
    }
} 