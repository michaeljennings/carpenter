<?php

namespace Michaeljennings\Carpenter\Contracts;

interface Cell
{
    /**
     * Get the cell value.
     *
     * @return string
     */
    public function getValue();

    /**
     * Alias for the get value method.
     *
     * @return string
     */
    public function value();

    /**
     * When converted to a string, return the cell value.
     *
     * @return string
     */
    public function __toString();
}