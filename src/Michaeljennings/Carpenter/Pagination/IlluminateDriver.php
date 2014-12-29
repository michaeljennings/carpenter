<?php namespace Michaeljennings\Carpenter\Pagination;

class IlluminateDriver implements PaginatorInterface {

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
        return $this->paginator->links(
            $this->app['config']['michaeljennings/carpenter::paginator.view']
        );
    }
} 