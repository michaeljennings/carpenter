<?php namespace Michaeljennings\Carpenter;

use Closure;
use Michaeljennings\Carpenter\Exceptions\CarpenterCollectionException;
use Michaeljennings\Carpenter\Exceptions\TableLocationNotFound;

class Carpenter {

    /**
     * A collection of table closures
     *
     * @var array
     */
    protected $collection = array();

    public function __construct($driverContainer, $config)
    {
        $this->driverContainer = $driverContainer;
        $this->config = $config;
    }

    /**
     * Add a table closure into the table collection
     *
     * @param string   $name
     * @param callable $table
     */
    public function add($name, Closure $table)
    {
        $this->collection[$name] = $table;
    }

    /**
     * Retrieve a table from the table collection and run the closure
     *
     * @param  string $name
     * @return Table
     * @throws CarpenterCollectionException
     */
    public function get($name)
    {
        $this->loadTables();

        if ( ! array_key_exists($name, $this->collection)) {
            throw new CarpenterCollectionException("No table was found with the name '{$name}'");
        }

        return new Table($name, $this->collection[$name], $this->driverContainer, $this->config);
    }

    /**
     * Create a new table without using the table collection.
     *
     * @param string   $name
     * @param callable $callback
     * @return Table
     */
    public function make($name, Closure $callback)
    {
        return new Table($name, $callback, $this->driverContainer, $this->config);
    }

    /**
     * Load all of the table instances from the tables file.
     *
     * @throws TableLocationNotFound
     */
    public function loadTables()
    {
        $tableLocations = $this->config['tables']['location'];

        if (file_exists($tableLocations)) {
            require_once $tableLocations;
        } else {
            throw new TableLocationNotFound("No file found for the path '{$tableLocations}'");
        }
    }
}