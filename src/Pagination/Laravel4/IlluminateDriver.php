<?php

namespace Michaeljennings\Carpenter\Pagination\Laravel4;

use Michaeljennings\Carpenter\Contracts\Paginator as PaginatorContract;

class IlluminateDriver implements PaginatorContract
{
    /**
     * An instance of the IOC container.
     *
     * @var mixed
     */
    protected $app;

    /**
     * The illuminate paginator.
     *
     * @var mixed
     */
    protected $paginator;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Create a new paginator.
     *
     * @param  string|integer $total
     * @param  string|integer $perPage
     * @return $this
     */
    public function make($total, $perPage)
    {
        $this->paginator = $this->app['paginator']->make([], $total, $perPage);

        return $this;
    }

    /**
     * Get the pagination links.
     *
     * @return string
     */
    public function links()
    {
        return $this->paginator->links();
    }

    /**
     * Get the current page.
     *
     * @return integer|string
     */
    public function currentPage()
    {
        return $this->paginator->getCurrentPage();
    }
} 