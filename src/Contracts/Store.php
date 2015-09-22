<?php

namespace Michaeljennings\Carpenter\Contracts;

interface Store
{
    /**
     * Return all of the results.
     *
     * @return array
     */
    public function results();

    /**
     * Return a count of all of the results.
     *
     * @return int
     */
    public function count();

    /**
     * Return a paginated list of results.
     *
     * @param $amount
     * @param $page
     * @param $perPage
     * @return array
     */
    public function paginate($amount, $page, $perPage);

    /**
     * Order the results by the given column in the given direction.
     *
     * @param string $key
     * @param string $direction
     * @return mixed
     */
    public function orderBy($key, $direction = 'asc');

    /**
     * Unset any set order queries.
     *
     * @return mixed
     */
    public function refreshOrderBy();
}