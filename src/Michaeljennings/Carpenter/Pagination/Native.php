<?php namespace Michaeljennings\Carpenter\Pagination;

use Michaeljennings\Carpenter\Contracts\Paginator;

class Native implements Paginator {

    /**
     * Create a new paginator.
     *
     * @param  string|integer $total
     * @param  string|integer $perPage
     * @return mixed
     */
    public function make($total, $perPage)
    {
        // TODO: Implement make() method.
    }

    /**
     * Get the pagination links.
     *
     * @return string
     */
    public function links()
    {
        // TODO: Implement links() method.
    }

    /**
     * Get the current page.
     *
     * @return integer|string
     */
    public function currentPage()
    {
        // TODO: Implement currentPage() method.
    }

}