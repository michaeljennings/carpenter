<?php

namespace Michaeljennings\Carpenter\Session;

use Michaeljennings\Carpenter\Contracts\Session as SessionContract;

class NativeDriver implements SessionContract
{
    /**
     * The notifier config.
     *
     * @var array
     */
    protected $config = [];

    /**
     * An array of flashed data.
     *
     * @var array
     */
    protected $flash = [];

    /**
     * Flag to state if this is the first time the class has be instantiated.
     *
     * @var bool
     */
    protected $initialLoad = true;

    public function __construct(array $config)
    {
        $this->config = $config;

        if ( ! isset($_SESSION)) {
            session_start();
        }

        if ($this->initialLoad) {
            if (
                isset($_SESSION[$this->config['session']['key']]) &&
                isset($_SESSION[$this->config['session']['key']]['flash'])
            ) {
                $this->flash = $_SESSION[$this->config['session']['key']]['flash'];
                unset($_SESSION[$this->config['session']['key']]['flash']);
            }

            $this->initialLoad = false;
        }
    }

    /**
     * Check if an item exists in the session.
     *
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        $session = $this->getSessionData($key);

        return array_key_exists($key, $session);
    }

    /**
     * Retrieve a property from the session by its key.
     *
     * @param $key
     * @return mixed|bool
     */
    public function get($key)
    {
        if ($this->has($key)) {
            $session = $this->getSessionData($key);

            return $session[$key];
        }

        return false;
    }

    /**
     * Put an item in the session.
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public function put($key, $value)
    {
        return $_SESSION[$key] = $value;
    }

    /**
     * Remove an item from the session.
     *
     * @param $key
     * @return void
     */
    public function forget($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }

        if (isset($this->flash[$key])) {
            unset($this->flash[$key]);
        }
    }

    /**
     * Flash an item to the session.
     *
     * @param $key
     * @param $value
     * @return void
     */
    public function flash($key, $value)
    {
        $_SESSION[$this->config['session']['key']]['flash'][$key] = $value;
        $this->flash[$key] = $value;
    }

    /**
     * Merge all of the notifier session data and any flashed data.
     *
     * @return array
     */
    protected function getSessionData($key)
    {
        $data = isset($_SESSION[$key]) ? $_SESSION[$key] : [];

        if ( ! is_array($data)) {
            $data = [$key => $data];
        }

        return array_merge($data, $this->flash);
    }
}