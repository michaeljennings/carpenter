<?php namespace Michaeljennings\Carpenter\Wrappers;

class ArrayWrapper {

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
     * Get a value from the item.
     *
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->item[$key];
    }

}