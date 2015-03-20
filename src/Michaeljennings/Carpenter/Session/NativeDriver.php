<?php namespace Michaeljennings\Carpenter\Session;

use Michaeljennings\Carpenter\Contracts\Session as SessionInterface;

class NativeDriver implements SessionInterface {

	/**
	 * Flashed session data.
	 * 
	 * @var array
	 */
	protected $flashData = [];

	/**
	 * Flag for whether this is the first time this class has been constructed.
	 * 
	 * @var boolean
	 */
	protected $firstRequest = true;

	public function __construct()
	{
		if ($firstRequest) {
			$this->flashData = $this->get('flashdata');
			$this->forget('flashdata');

			$firstRequest = false;
		}
	}

	/**
     * Retrieve an item from the session
     *
     * @param  string $name
     * @return mixed
     */
    public function get($name)
    {
    	if (isset($_SESSION[$name]) {
    		return $_SESSION[$name];
    	}

    	if (isset($this->flashData[$name])) {
    		return $this->flashData[$name];
    	}

    	return null;
    }

    /**
     * Store an item in the session
     *
     * @param  string $name
     * @param  mixed  $value
     * @return mixed
     */
    public function put($name, $value)
    {
    	if ($this->has($name)) {
    		if (is_array($this->get($name))) {
    			$value = array_merge($value, $this->get($name));
    		}
    	}

    	return $_SESSION[$name] = $value;
    }

    /**
     * Flash a value to the session
     *
     * @param  string $name
     * @param  mixed  $value
     * @return mixed
     */
    public function flash($name, $value)
    {
    	return $this->put(['flashdata' => $name], $value);
    }

    /**
     * Check if a value is set in the session
     *
     * @param  string $name
     * @return mixed
     */
    public function has($name)
    {
    	if ( ! empty($_SESSION[$name])) {
    		return true;
    	}

    	if ( ! empty($this->flashData[$name])) {
    		return true;
    	}

    	return false;
    }

    /**
     * Remove a value from the session
     *
     * @param  string $name
     * @return mixed
     */
    public function forget($name)
    {
    	return unset($_SESSION[$name]);
    }

}