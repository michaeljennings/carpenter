<?php

namespace Michaeljennings\Carpenter\Nexus;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

class Container implements ArrayAccess, IteratorAggregate
{
    /**
     * The items in the container.
     *
     * @var array
     */
    protected $items = [];

    public function __construct(array $items, array $config)
    {
        foreach ($items as &$item) {
            $item = new MockArray($item);
        }

        $this->items = $items;
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->items[$key];
    }

    /**
     * Set the item at a given offset.
     *
     * @param  mixed $key
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->items[$key] = $value;
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }
}