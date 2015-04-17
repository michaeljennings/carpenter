<?php namespace Michaeljennings\Carpenter\Contracts;

interface Store {

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
     * @return array
     */
    public function paginate($amount, $page);

    /**
     * Unset any set order queries.
     *
     * @return mixed
     */
    public function refreshOrderBy();

}