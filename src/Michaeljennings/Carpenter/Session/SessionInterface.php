<?php namespace Michaeljennings\Carpenter\Session;

interface SessionInterface {

    /**
     * Retrieve an item from the session
     *
     * @param  string $name
     * @return mixed
     */
    public function get($name);

    /**
     * Store an item in the session
     *
     * @param  string $name
     * @param  mixed  $value
     * @return mixed
     */
    public function put($name, $value);

    /**
     * Flash a value to the session
     *
     * @param  string $name
     * @param  mixed  $value
     * @return mixed
     */
    public function flash($name, $value);

    /**
     * Check if a value is set in the session
     *
     * @param  string $name
     * @return mixed
     */
    public function has($name);

    /**
     * Remove a value from the session
     *
     * @param  string $name
     * @return mixed
     */
    public function forget($name);
} 