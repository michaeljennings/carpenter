<?php namespace Michaeljennings\Carpenter\Store;

class ArrayStore {

    protected $data = array();

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
     * @return array
     */
    public function paginate($amount, $page)
    {
        $chunks = array_chunk($this->data, $amount);
        $offset = $page - 1;

        return $chunks[$offset];
    }

}