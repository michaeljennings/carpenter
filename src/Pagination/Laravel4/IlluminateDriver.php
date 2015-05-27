<?php namespace Michaeljennings\Carpenter\Pagination\Laravel4;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\BootstrapThreePresenter;
use Michaeljennings\Carpenter\Contracts\Paginator as PaginatorContract;

class IlluminateDriver implements PaginatorContract {

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
     * @return mixed
     */
    public function make($total, $perPage)
    {
        $this->paginator = $this->app['paginator']->make(array(), $total, $perPage);

        return $this->paginator;
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