<?php namespace Michaeljennings\Carpenter\Pagination;

use Illuminate\Support\Manager;

class PaginationManager extends Manager {

    public function createIlluminateDriver()
    {
        return new IlluminateDriver($this->app);
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['michaeljennings/carpenter::paginator.driver'];
    }

} 