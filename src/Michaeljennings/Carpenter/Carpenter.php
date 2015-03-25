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
     * @param callable|string $table
     */
    public function add($name, $table)
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
        if ( ! array_key_exists($name, $this->collection)) {
            throw new CarpenterCollectionException("No table was found with the name '{$name}'");
        }

        $callback = $this->collection[$name];

        if (is_string($callback)) {
            $callback = $this->buildClassCallback($callback);
        }

        return $this->buildTable($name, $callback);
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
        return $this->buildTable($name, $callback);
    }

    /**
     * Run the callback on the a table instance and then return the table.
     *
     * @param string $name
     * @param Closure $callback
     * @return Table
     */
    protected function buildTable($name, Closure $callback)
    {
        $table = new Table($name, $this->driverContainer, $this->config);
        $callback($table);

        return $table;
    }

    /**
     * Build a new callback from a class for the class based tables.
     *
     * @param string $callback
     * @return callable
     */
    protected function buildClassCallback($callback)
    {
        list($class, $method) = $this->parseClassCallback($callback);

        return function() use ($class, $method)
        {
            $callable = array(new $class, $method);

            return call_user_func_array($callable, func_get_args());
        };
    }

    /**
     * Parse the class based table name to the class name and method.
     *
     * @param string $class
     * @return array
     */
    protected function parseClassCallback($class)
    {
        if (str_contains($class, '@')) {
            return explode('@', $class);
        }

        return array($class, 'build');
    }

    /**
     * Load all of the table instances from the tables file.
     *
     * @throws TableLocationNotFound
     *
     * @depreciated This function is required if you are storing tables in a file, not
     * in service providers.
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