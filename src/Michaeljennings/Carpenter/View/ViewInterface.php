<?php namespace Michaeljennings\Carpenter\View;

interface ViewInterface {

    /**
     * Return the required view
     *
     * @param $view
     * @param array $data
     * @return mixed
     */
    public function make($view, $data = array());

} 