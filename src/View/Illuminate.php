<?php

namespace Michaeljennings\Carpenter\View;

use Illuminate\View\Factory;
use Michaeljennings\Carpenter\Contracts\View as ViewInterface;
use Michaeljennings\Carpenter\Exceptions\ViewNotFoundException;

class Illuminate implements ViewInterface
{
    /**
     * An instance of the illuminate view class
     *
     * @var mixed
     */
    protected $view;

    public function __construct(Factory $view)
    {
        $this->view = $view;
    }

    /**
     * Return the required view
     *
     * @param string $view
     * @param array  $data
     * @return string
     * @throws ViewNotFoundException
     */
    public function make($view, $data = [])
    {
        try {
            return $this->view->make($view, $data)->render();
        } catch (\InvalidArgumentException $e) {
            throw new ViewNotFoundException("No file found at '{$view}'");
        }
    }
} 