<?php namespace Michaeljennings\Carpenter\Store; 

use Michaeljennings\Carpenter\Contracts\Store;

class CodeigniterStore implements Store {

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
    	return $this->query->count_all_results();
    }

    /**
     * Return a paginated list of results.
     *
     * @param $amount
     * @param $page
     * @return array
     */
    public function paginate($amount, $page)
    {
    	$this->query = $this->db->limit($limit, $page);
    	$query = $this->query->get();

    	return $query->results();
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

    	$this->query = $this->db->$method($args);

    	return $this;
    }

    /**
	 * __get
	 * 
	 * Enables the use of CI super-global without having to define an extra 
     * variable.
	 *
	 * @access  public
	 * @param   $var
	 * @return  mixed
	 */
	public function __get($var) {
		return get_instance()->$var;
	}

}