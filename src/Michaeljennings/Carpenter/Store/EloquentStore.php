<?php namespace Michaeljennings\Carpenter\Store; 

class EloquentStore {

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
    protected $select = array('*');

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
     * Return the total results.
     *
     * @return integer
     */
    public function count()
    {
        return $this->model->paginate(1, $this->select)->total();
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
     * @return mixed
     */
    public function results()
    {
        return $this->model->get($this->select);
    }

    public function __call($method, $args)
    {
        $this->model = call_user_func_array([$this->model, $method], $args);

        return $this->model;
    }

}