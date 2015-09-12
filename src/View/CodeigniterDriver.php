<?php

namespace Michaeljennings\Carpenter\View;

use Michaeljennings\Carpenter\Contracts\View as ViewInterface;

class CodeigniterDriver implements ViewInterface
{
    /**
     * Return the required view
     *
     * @param       $view
     * @param array $data
     * @return string
     */
    public function make($view, $data = [])
    {
        return $this->load->view($view, $data, true);
    }

    /**
     * __get
     *
     * Enables the use of CI super-global without having to define an extra
     * variable.
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