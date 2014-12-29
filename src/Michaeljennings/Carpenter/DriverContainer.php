<?php namespace Michaeljennings\Carpenter;

use Michaeljennings\Carpenter\Exceptions\DriverNotFoundException;

class DriverContainer {

    /**
     * An array of drivers
     *
     * @var array
     */
    protected $drivers = array();

    /**
     * Set the carpenter drivers
     *
     * @param array $drivers
     */
    public function __construct(array $drivers)
    {
        $this->drivers = $drivers;
    }

    /**
     * Use the magic get method to access multiple drivers from one
     * object.
     *
     * @param  string $name
     * @return mixed
     * @throws DriverNotFoundException
     */
    function __get($name)
    {
        if (array_key_exists($name, $this->drivers)) {
            return $this->drivers[$name];
        } else {
            throw new DriverNotFoundException("No driver set with the name '{$name}'");
        }
    }

} 