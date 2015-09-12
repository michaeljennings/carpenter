<?php

namespace Michaeljennings\Carpenter\Contracts;

interface View
{
    /**
     * Return the required view
     *
     * @param       $view
     * @param array $data
     * @return string
     */
    public function make($view, $data = []);
}