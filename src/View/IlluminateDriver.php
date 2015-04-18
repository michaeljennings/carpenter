<?php namespace Michaeljennings\Carpenter\View;

use Michaeljennings\Carpenter\Contracts\View as ViewInterface;

class IlluminateDriver implements ViewInterface {

    /**
     * An instance of the illuminate view class
     *
     * @var mixed
     */
    protected $view;

    public function __construct($view)
    {
        $this->view = $view;
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
        return $this->view->make($view, $data)->render();
    }

} 