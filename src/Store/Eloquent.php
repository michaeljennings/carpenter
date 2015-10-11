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
     * The store data wrapper.
     *
     * @var string
     */
    protected $wrapper = 'Michaeljennings\Carpenter\Wrappers\ObjectWrapper';

    /**
     * Set the model to be used for the table.
     *
     * @param mixed $model
     * @return $this
     */
    public function model($model)
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
     * @throws ModelNotSetException
     */
    public function results()
    {
        if ( ! $this->model) {
            throw new ModelNotSetException('You must set a model to be used by the eloquent driver.');
        }

        return $this->model->get($this->select)->all();
    }

    /**
     * Return the total results.
     *
     * @return integer
     * @throws ModelNotSetException
     */
    public function count()
    {
        if ( ! $this->model) {
            throw new ModelNotSetException('You must set a model to be used by the eloquent driver.');
        }

        $model = clone $this->model;

        return $model->paginate(1, $this->select)->total();
    }

    /**
     * Return a paginated list of results.
     *
     * @param int|string $amount
     * @param int|string $page
     * @param int|string $perPage
     * @return array
     * @throws ModelNotSetException
     */
    public function paginate($amount, $page, $perPage)
    {
        if ( ! $this->model) {
            throw new ModelNotSetException('You must set a model to be used by the eloquent driver.');
        }

        return $this->model->paginate($amount, $this->select)->all();
    }

    /**
     * Remove any order by statements.
     *
     * @return $this
     * @throws ModelNotSetException
     */
    public function refreshOrderBy()
    {
        if ( ! $this->model) {
            throw new ModelNotSetException('You must set a model to be used by the eloquent driver.');
        }

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
     * @throws ModelNotSetException
     */
    public function orderBy($key, $direction = 'asc')
    {
        if ( ! $this->model) {
            throw new ModelNotSetException('You must set a model to run queries on.');
        }

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