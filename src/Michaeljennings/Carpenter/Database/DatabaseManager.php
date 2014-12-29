<?php namespace Michaeljennings\Carpenter\Database;

use Illuminate\Support\Manager;

class DatabaseManager extends Manager
{

    /**
     * Create the eloquent database driver
     *
     * @return EloquentDriver
     */
    public function createEloquentDriver()
    {
        return new EloquentDriver();
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['michaeljennings/carpenter::database.driver'];
    }

}