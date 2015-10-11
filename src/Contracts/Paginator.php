<?php

namespace Michaeljennings\Carpenter\Contracts;

interface Paginator
{
    /**
     * Create a new paginator.
     *
     * @param  string|integer $total
     * @param  string|integer $perPage
     * @param  string         $tableKey
     * @return Paginator
     */
    public function make($total, $perPage, $tableKey);

    /**
     * Get the pagination links.
     *
     * @return string
     */
    public function links();

    /**
     * Get the current page.
     *
     * @return integer|string
     */
    public function currentPage();
}