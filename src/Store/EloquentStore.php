<?php

namespace Michaeljennings\Carpenter\Store;

use Michaeljennings\Carpenter\Contracts\Store;

class EloquentStore implements Store
{
    /**
     * The eloquent model to get results from.
     *
     * @var mixed
     */
    protected $model;

    /**
     * An array of columns to select.
     *
     * @var array
     */
    protected $select = ['*'];

    /**
     * Set the model to be used for the table.
     *
     * @param $model
     */
    public function model($model)
    {
        $this->model = new $model;
    }

    /**
     * Set the columns to select from the database.
     *
     * @param array $columns
     */
    public function select(array $columns)
    {
        $this->select = $columns;
    }

    /**
     * Get the results for the model.
     *
     * @return array
     */
    public function results()
    {
        return $this->model->get($this->select)->all();
    }

    /**
     * Return the total results.
     *
     * @return integer
     */
    public function count()
    {
        $model = clone $this->model;

        return $model->paginate(1, $this->select)->total();
    }

    /**
     * Get a paginate
     *
     * @param $amount
     * @param $page
     * @param $perPage
     * @return array
     */
    public function paginate($amount, $page, $perPage)
    {
        return $this->model->paginate($amount)->all();
    }

    /**
     * Remove any order by statements.
     *
     * @return $this
     */
    public function refreshOrderBy()
    {
        $query = $this->model->getQuery();
        unset($query->orders);

        $this->model->setQuery($query);

        return $this;
    }

    /**
     * Order the results by the given column in the given direction.
     *
     * @param $key
     * @param $direction
     * @return $this
     */
    public function orderBy($key, $direction = 'asc')
    {
        $this->model = $this->model->orderBy($key, $direction);

        return $this;
    }

    public function __call($method, $args)
    {
        $this->model = call_user_func_array([$this->model, $method], $args);

        return $this->model;
    }
}