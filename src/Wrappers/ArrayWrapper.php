<?php

namespace Michaeljennings\Carpenter\Wrappers;

class ArrayWrapper
{
    /**
     * The item being wrapped.
     *
     * @var array
     */
    protected $item;

    /**
     * @param array $item
     */
    public function __construct(array $item)
    {
        $this->item = $item;
    }

    /**
     * Check if the provided key is set in the array.
     *
     * @param $key string
     * @return bool
     */
    function __isset($key)
    {
        return isset($this->item[$key]);
    }


    /**
     * Get a value from the item.
     *
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        if (isset($this->item[$key])) {
            return $this->item[$key];
        }

        return null;
    }
}