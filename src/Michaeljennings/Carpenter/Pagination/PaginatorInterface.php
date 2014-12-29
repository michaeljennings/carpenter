<?php namespace Michaeljennings\Carpenter\Pagination;


interface PaginatorInterface {

    /**
     * Create a new paginator.
     *
     * @param  string|integer $total
     * @param  string|integer $perPage
     * @return mixed
     */
    public function make($total, $perPage);

    /**
     * Get the pagination links.
     *
     * @return string
     */
    public function links();

} 