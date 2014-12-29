<?php namespace Michaeljennings\Carpenter\Database;

class EloquentDriver implements DatabaseInterface {

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
     * Set the eloquent model.
     *
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Return the total results.
     *
     * @return integer
     */
    public function count()
    {
        $countModel = clone $this->model;
        $countQuery = $countModel->getQuery();
        $countQuery->orders = null;
        $countModel->setQuery($countQuery);

        return $countModel->count();
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
        $this->select = $select;
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

    /**
     * Catch any unspecified methods and run them on the model.
     *
     * @param string $method
     * @param array  $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        $this->model = call_user_func_array(array($this->model, $method), $args);
        return $this->model;
    }
}