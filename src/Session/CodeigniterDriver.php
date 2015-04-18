<?php namespace Michaeljennings\Carpenter\Session;

use Michaeljennings\Carpenter\Contracts\Session as SessionInterface;

class CodeigniterDriver implements SessionInterface {

	public function __construct()
	{
		$ci =& get_instance();

		$this->session = $ci->load->library('session');
	}

	/**
     * Retrieve an item from the session
     *
     * @param  string $name
     * @return mixed
     */
    public function get($name)
    {
    	return $this->session->userdata($name);
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
    	return $this->session->set_userdata([$name => $value]);
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
    	return $this->session->set_flashdata($name, $value);
    }

    /**
     * Check if a value is set in the session
     *
     * @param  string $name
     * @return mixed
     */
    public function has($name)
    {
    	return ! empty($this->get($name));
    }

    /**
     * Remove a value from the session
     *
     * @param  string $name
     * @return mixed
     */
    public function forget($name)
    {
    	return $this->session->unset_userdata([$name => '']);
    }

}