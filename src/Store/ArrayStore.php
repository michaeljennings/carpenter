<?php

namespace Michaeljennings\Carpenter\Store;

use Michaeljennings\Carpenter\Contracts\Store;

class ArrayStore implements Store
{
    /**
     * The store data.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Set the data to be used by the array store.
     *
     * @param array $data
     * @return $this
     */
    public function data(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Return all of the results.
     *
     * @return array
     */
    public function results()
    {
        return $this->data;
    }

    /**
     * Return a count of all of the results.
     *
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Return a paginated list of results.
     *
     * @param $amount
     * @param $page
     * @param $perPage
     * @return array
     */
    public function paginate($amount, $page, $perPage)
    {
        $chunks = array_chunk($this->data, $perPage);
        $offset = $page - 1;

        return $chunks[$offset];
    }

    /**
     * Order the results by the given column in the given direction.
     *
     * @param $key
     * @param $direction
     */
    public function orderBy($key, $direction = 'asc')
    {
        $direction = strtolower($direction) == 'asc' ? SORT_ASC : SORT_DESC;
        $sort_col = [];

        foreach ($this->data as $col => $row) {
            $sort_col[$col] = $row[$key];
        }

        array_multisort($sort_col, $direction, $this->data);
    }

    /**
     * Unset any set order queries.
     *
     * @return $this
     */
    public function refreshOrderBy()
    {
        return $this;
    }
}