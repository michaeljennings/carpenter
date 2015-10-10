<?php

namespace Michaeljennings\Carpenter\Store;

use Michaeljennings\Carpenter\Contracts\Store;

class Codeigniter implements Store
{
    /**
     * The name of the model we are accessing.
     *
     * @var string
     */
    protected $model;

    /**
     * The query to be run.
     *
     * @var mixed
     */
    protected $query;

    public function model($model)
    {
        $this->model = $model;
        $this->load->model($model);

        return $this;
    }

    /**
     * Return all of the results.
     *
     * @return array
     */
    public function results()
    {
        if (isset($this->query)) {
            $query = $this->query->get();
        } else {
            $query = $this->db->get();
        }

        return $query->result();
    }

    /**
     * Return a count of all of the results.
     *
     * @return int
     */
    public function count()
    {
        if ( ! is_null($this->query)) {
            $query = clone $this->query;

            return $query->count_all_results();
        }

        return 0;
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
        $offset = ($page - 1) * $perPage;

        if (isset($this->query)) {
            $this->query->limit($amount, $offset);
        } else {
            $this->query = $this->db->limit($amount, $offset);
        }

        $query = $this->query->get();

        return $query->result();
    }

    /**
     * Add an order by query.
     *
     * @param  string $column
     * @param  string $dir
     * @return $this
     */
    public function orderBy($column, $dir = 'asc')
    {
        return $this->order_by($column, $dir);
    }

    /**
     * Unset any set order queries.
     *
     * @return mixed
     */
    public function refreshOrderBy()
    {
        return $this;
    }

    /**
     * Catch any undefined functions then check if a model has been set and the
     * method exists on that model. If so return the method else run the
     * caught method on the db class.
     *
     * @param  string $method
     * @param  array  $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (isset($this->model) && method_exists($this->{$this->model}, $method)) {
            return call_user_func_array([$this->{$this->model}, $method], $args);
        }

        if ( ! isset($this->query)) {
            $this->query = call_user_func_array([$this->db, $method], $args);
        } else {
            call_user_func_array([$this->query, $method], $args);
        }

        return $this;
    }

    /**
     * __get
     *
     * Enables the use of CI super-global without having to define an extra
     * variable.
     *
     * @param string $var
     * @return  mixed
     */
    public function __get($var)
    {
        return get_instance()->$var;
    }
}