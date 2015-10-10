<?php

namespace Michaeljennings\Carpenter\Store;

use Illuminate\Database\Eloquent\Model;
use Michaeljennings\Carpenter\Contracts\Store;
use Michaeljennings\Carpenter\Exceptions\ModelNotSetException;

class Eloquent implements Store
{
    /**
     * The eloquent model to get results from.
     *
     * @var Model|null
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
     * @param Model $model
     * @return $this
     */
    public function model(Model $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Set the columns to select from the database.
     *
     * @param array $columns
     * @return $this
     */
    public function select(array $columns)
    {
        $this->select = $columns;

        return $this;
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
        return $this->model->paginate($amount, $this->select)->all();
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
     * @param string      $key
     * @param string|null $direction
     * @return $this
     */
    public function orderBy($key, $direction = 'asc')
    {
        $this->model = $this->model->orderBy($key, $direction);

        return $this;
    }

    /**
     * Catch any unspecified methods and run them on the model.
     *
     * @param string $method
     * @param array  $args
     * @return $this
     * @throws ModelNotSetException
     */
    public function __call($method, $args)
    {
        if ( ! $this->model) {
            throw new ModelNotSetException('You must set a model to run queries on.');
        }

        $this->model = call_user_func_array([$this->model, $method], $args);

        return $this;
    }
}