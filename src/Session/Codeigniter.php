<?php

namespace Michaeljennings\Carpenter\Session;

use Michaeljennings\Carpenter\Contracts\Session as SessionInterface;

class Codeigniter implements SessionInterface
{
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
        $value = $this->get($name);

        return ! empty($value);
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

    /**
     * __get
     *
     * Enables the use of CI super-global without having to define an extra variable.
     *
     * I can't remember where I first saw this, so thank you if you are the original author. -Militis
     *
     * @access  public
     * @param   $var
     * @return  mixed
     */
    public function __get($var)
    {
        return get_instance()->$var;
    }
}