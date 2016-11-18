<?php

namespace Michaeljennings\Carpenter\Wrappers;

use ArrayAccess;
use Michaeljennings\Carpenter\Contracts\Wrapper;

class ArrayWrapper implements ArrayAccess, Wrapper
{
    /**
     * The item being wrapped.
     *
     * @var array
     */
    protected $item;

    public function __construct($item)
    {
        if ( ! is_array($item)) {
            $item = (array) $item;
        }

        $this->item = $item;
    }

    /**
     * Determine if the given offset exists.
     *
     * @param  string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->item[$offset]);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->item[$offset];
    }

    /**
     * Set the value at the given offset.
     *
     * @param  string $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->item[$offset] = $value;
    }

    /**
     * Unset the value at the given offset.
     *
     * @param  string $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->item[$offset]);
    }

    /**
     * Retrieve a value from the item.
     *
     * @param $name string
     * @return mixed
     */
    function __get($name)
    {
        if($this->offsetExists($name)) {
            return $this->item[$name];
        }
        
        return null;
    }

    /**
     * Set a key and value on the item.
     *
     * @param $name  string
     * @param $value mixed
     * @return void
     */
    function __set($name, $value)
    {
        $this->item[$name] = $value;
    }

    /**
     * Check if a key is set on the item.
     *
     * @param $name string
     * @return bool
     */
    function __isset($name)
    {
        return isset($this->item[$name]);
    }

    /**
     * Unset a key from the item.
     *
     * @param $name string
     * @return void
     */
    function __unset($name)
    {
        unset($this->item[$name]);
    }

    /**
     * Get the original item
     *
     * @return mixed
     */
    public function getItem()
    {
        return $this->item;
    }
}
