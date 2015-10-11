<?php

namespace Michaeljennings\Carpenter\Store;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Query\Builder;
use Michaeljennings\Carpenter\Contracts\Store;
use Michaeljennings\Carpenter\Exceptions\ModelNotAvailableException;
use Michaeljennings\Carpenter\Exceptions\TableNotSetException;

class Illuminate extends AbstractStore implements Store
{
    /**
     * The database table to be used by the query.
     *
     * @var string|null
     */
    protected $table;

    /**
     * An array of columns to select.
     *
     * @var array
     */
    protected $select = ['*'];

    /**
     * An instance of the illuminate database manager.
     *
     * @var DatabaseManager
     */
    protected $db;

    /**
     * The query to be run.
     *
     * @var Builder|null
     */
    protected $query;

    /**
     * The store data wrapper.
     *
     * @var string
     */
    protected $wrapper = 'Michaeljennings\Carpenter\Wrappers\ObjectWrapper';

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    /**
     * Set the model to be used for the table.
     *
     * @param mixed $model
     * @return Store
     * @throws ModelNotAvailableException
     */
    public function model($model)
    {
        throw new ModelNotAvailableException('You can not set a model when using the illuminate store.');
    }

    /**
     * Set the database table to be used in the query.
     *
     * @param string $table
     * @return $this
     */
    public function table($table)
    {
        $this->query = $this->db->table($table);

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
     * Return all of the results.
     *
     * @return array
     * @throws TableNotSetException
     */
    public function results()
    {
        if ( ! $this->query) {
            throw new TableNotSetException("You must set a database table to get results from.");
        }

        return $this->query->get($this->select);
    }

    /**
     * Return a count of all of the results.
     *
     * @return int
     * @throws TableNotSetException
     */
    public function count()
    {
        if ( ! $this->query) {
            throw new TableNotSetException("You must set a database table to get results from.");
        }

        $query = clone $this->query;

        return $query->paginate(1, $this->select)->total();
    }

    /**
     * Return a paginated list of results.
     *
     * @param int|string $amount
     * @param int|string $page
     * @param int|string $perPage
     * @return array
     * @throws TableNotSetException
     */
    public function paginate($amount, $page, $perPage)
    {
        if ( ! $this->query) {
            throw new TableNotSetException("You must set a database table to get results from.");
        }

        return $this->query->paginate($amount, $this->select, $this->key)->all();
    }

    /**
     * Order the results by the given column in the given direction.
     *
     * @param string $key
     * @param string $direction
     * @return $this
     * @throws TableNotSetException
     */
    public function orderBy($key, $direction = 'asc')
    {
        if ( ! $this->query) {
            throw new TableNotSetException("You must set a database table to get results from.");
        }

        $this->query = $this->query->orderBy($key, $direction);

        return $this;
    }

    /**
     * Unset any set order queries.
     *
     * @return mixed
     * @throws TableNotSetException
     */
    public function refreshOrderBy()
    {
        if ( ! $this->query) {
            throw new TableNotSetException("You must set a database table to get results from.");
        }

        unset($this->query->orders);

        return $this;
    }

    /**
     * Catch any unspecified methods and run them on the query.
     *
     * @param string $method
     * @param array  $args
     * @return $this
     * @throws TableNotSetException
     */
    public function __call($method, $args)
    {
        if ( ! $this->query) {
            throw new TableNotSetException("You must set a database table to get results from.");
        }

        $this->query = call_user_func_array([$this->query, $method], $args);

        return $this;
    }
}