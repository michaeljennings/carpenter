<?php

namespace Michaeljennings\Carpenter\Contracts;

interface View
{
    /**
     * Return the required view
     *
     * @param string $view
     * @param array  $data
     * @return string
     * @throws ViewNotFoundException
     */
    public function make($view, $data = []);
}