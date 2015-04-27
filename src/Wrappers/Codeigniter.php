<?php namespace Michaeljennings\Carpenter\Wrappers;

use Illuminate\Database\Eloquent\Model;

class Codeigniter {

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
            $item = (array) $item;
        }

        $this->item = $item;
    }

    /**
     * Return an value from the item.
     *
     * @param $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->item->$key;
    }

}