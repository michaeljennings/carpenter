<?php

namespace Michaeljennings\Carpenter\Contracts;

interface Wrapper
{
    /**
     * Retrieve a value from the item.
     *
     * @param $name string
     * @return mixed
     */
    function __get($name);

    /**
     * Set a key and value on the item.
     *
     * @param $name  string
     * @param $value mixed
     * @return void
     */
    function __set($name, $value);

    /**
     * Check if a key is set on the item.
     *
     * @param $name string
     * @return bool
     */
    function __isset($name);

    /**
     * Unset a key from the item.
     *
     * @param $name string
     * @return void
     */
    function __unset($name);

    /**
     * Get the original item
     *
     * @return mixed
     */
    public function getItem();
}