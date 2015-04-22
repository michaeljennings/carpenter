<?php namespace Michaeljennings\Carpenter\Wrappers;

use Illuminate\Database\Eloquent\Model;

class Eloquent {

    /**
     * The item being wrapped.
     *
     * @var Model
     */
    protected $item;

    public function __construct(Model $item)
    {
        $this->item = $item;
    }

    /**
     * Return an value from the item.
     *
     * @param $key
     * @return $this|bool|\Carbon\Carbon|\DateTime|mixed|static
     */
    public function __get($key)
    {
        return $this->item->$key;
    }

}