<?php

namespace Michaeljennings\Carpenter\Contracts;

use Closure;

interface Carpenter
{
    /**
     * Add a table closure into the table collection
     *
     * @param string          $name
     * @param callable|string $table
     */
    public function add($name, $table);

    /**
     * Retrieve a table from the table collection and run the closure
     *
     * @param  string $name
     * @return Table
     * @throws CarpenterCollectionException
     */
    public function get($name);

    /**
     * Create a new table without using the table collection.
     *
     * @param string   $name
     * @param callable $callback
     * @return Table
     */
    public function make($name, Closure $callback);

    /**
     * Set a manager extension.
     *
     * @param string         $manager
     * @param string         $key
     * @param string|Closure $extension
     * @return $this
     */
    public function extend($manager, $key, $extension);
}