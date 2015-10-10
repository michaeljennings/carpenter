<?php

namespace Michaeljennings\Carpenter\Contracts;

interface Store
{
    /**
     * Set the model to be used for the table.
     *
     * @param mixed $model
     * @return Store
     */
    public function model($model);

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
     * @param int|string $amount
     * @param int|string $page
     * @param int|string $perPage
     * @return array
     */
    public function paginate($amount, $page, $perPage);

    /**
     * Order the results by the given column in the given direction.
     *
     * @param string $key
     * @param string $direction
     * @return Store
     */
    public function orderBy($key, $direction = 'asc');

    /**
     * Unset any set order queries.
     *
     * @return mixed
     */
    public function refreshOrderBy();

    /**
     * Get the store data wrapper.
     *
     * @return string
     */
    public function getWrapper();
}