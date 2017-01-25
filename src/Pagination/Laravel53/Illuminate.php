<?php

namespace Michaeljennings\Carpenter\Pagination\Laravel53;

use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\BootstrapThreePresenter;
use Illuminate\Pagination\LengthAwarePaginator;
use Michaeljennings\Carpenter\Contracts\Paginator as PaginatorContract;

class Illuminate extends AbstractPaginator implements PaginatorContract
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
     * @param  string         $tableKey
     * @return $this
     */
    public function make($total, $perPage, $tableKey)
    {
        $this->paginator = new LengthAwarePaginator([], $total, $perPage, $this->app['request']->input($tableKey), [
            'path' => $this->app['request']->url(),
        ]);

        $this->paginator->setPageName($tableKey);

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