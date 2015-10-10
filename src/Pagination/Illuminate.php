<?php

namespace Michaeljennings\Carpenter\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\BootstrapThreePresenter;
use Michaeljennings\Carpenter\Contracts\Paginator as PaginatorContract;

class Illuminate implements PaginatorContract
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
        $this->paginator = new LengthAwarePaginator([], $total, $perPage, $this->app['request']->input('page'), [
            'path' => $this->app['request']->url(),
        ]);

        return $this;
    }

    /**
     * Get the pagination links.
     *
     * @return string
     */
    public function links()
    {
        return $this->paginator->render(new BootstrapThreePresenter($this->paginator));
    }

    /**
     * Get the current page.
     *
     * @return integer|string
     */
    public function currentPage()
    {
        return $this->paginator->currentPage();
    }
} 