<?php

namespace Michaeljennings\Carpenter\Store;

abstract class AbstractStore
{
    /**
     * The store data wrapper.
     *
     * @var string
     */
    protected $wrapper;

    /**
     * The unqiue table key.
     *
     * @var string
     */
    protected $key;

    /**
     * Set the model to be used for the table.
     *
     * @param mixed $model
     * @return Store
     */
    abstract public function model($model);

    /**
     * Return all of the results.
     *
     * @return array
     */
    abstract public function results();

    /**
     * Return a count of all of the results.
     *
     * @return int
     */
    abstract public function count();

    /**
     * Return a paginated list of results.
     *
     * @param int|string $amount
     * @param int|string $page
     * @param int|string $perPage
     * @return array
     */
    abstract public function paginate($amount, $page, $perPage);

    /**
     * Order the results by the given column in the given direction.
     *
     * @param string $key
     * @param string $direction
     * @return Store
     */
    abstract public function orderBy($key, $direction = 'asc');

    /**
     * Unset any set order queries.
     *
     * @return mixed
     */
    abstract public function refreshOrderBy();

    /**
     * Set the unique table key.
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Get the store data wrapper.
     *
     * @return string
     */
    public function getWrapper()
    {
        return $this->wrapper;
    }
}