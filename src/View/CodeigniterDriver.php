<?php namespace Michaeljennings\Carpenter\View;

use Michaeljennings\Carpenter\Contracts\View as ViewInterface;

class CodeigniterDriver implements ViewInterface {

	/**
     * An instance of the codeigniter instance class;
     *
     * @var mixed
     */
    protected $instance;

    public function __construct()
    {
        $this->instance =& get_instance();
    }

	/**
     * Return the required view
     *
     * @param $view
     * @param array $data
     * @return string
     */
    public function make($view, $data = array())
    {
    	return $this->instance->view($view, $data, true);
    }

}