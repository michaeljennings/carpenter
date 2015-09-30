<?php

namespace Michaeljennings\Carpenter\View;

use Michaeljennings\Carpenter\Contracts\View;
use Michaeljennings\Carpenter\Exceptions\ViewNotFoundException;

class Native implements View
{
    /**
     * Return the required view
     *
     * @param       $view
     * @param array $data
     * @return string
     * @throws ViewNotFoundException
     */
    public function make($view, $data = [])
    {
        if ( ! file_exists($view)) {
            throw new ViewNotFoundException("The table template could not be found. No file found at '{$view}'");
        }

        ob_start();

        if ( ! empty($data)) {
            extract($data);
        }

        include($view);

        return ob_get_clean();
    }
}