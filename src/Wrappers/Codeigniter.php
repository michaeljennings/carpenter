<?php

namespace Michaeljennings\Carpenter\Wrappers;

class Codeigniter
{
    /**
     * The item being wrapped.
     *
     * @var Model
     */
    protected $item;

    /**
     * @param mixed $item
     */
    public function __construct($item)
    {
        // Check if the item is an array, if not type cast it to an array.
        if ( ! is_array($item)) {
            $item = (array)$item;
        }

        $this->item = $item;
    }

    /**
     * Check if the provided key is set on the model.
     *
     * @param $key string
     * @return bool
     */
    function __isset($key)
    {
        return isset($this->item[$key]);
    }

    /**
     * Return an value from the item.
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