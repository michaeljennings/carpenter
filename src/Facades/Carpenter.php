<?php namespace Michaeljennings\Carpenter\Facades;

use Illuminate\Support\Facades\Facade;

class Carpenter extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'michaeljennings.carpenter'; }

} 