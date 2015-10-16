<?php

namespace Michaeljennings\Carpenter\Pagination;

abstract class AbstractPaginator
{
    /**
     * Create a new paginator.
     *
     * @param  string|integer $total
     * @param  string|integer $perPage
     * @param  string         $tableKey
     * @return $this
     */
    abstract public function make($total, $perPage, $tableKey);

    /**
     * Get the pagination links.
     *
     * @return string
     */
    abstract public function links();

    /**
     * Get the current page.
     *
     * @return integer|string
     */
    abstract public function currentPage();
}