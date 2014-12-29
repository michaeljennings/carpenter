<?php namespace Michaeljennings\Carpenter\Database;

interface DatabaseInterface {

    /**
     * Set the model
     *
     * @param mixed $model
     */
    public function setModel($model);

    /**
     * Return the total results
     *
     * @return integer
     */
    public function count();

    /**
     * Remove any order by statements
     *
     * @return $this
     */
    public function refreshOrderBy();

    /**
     * Set the columns to select from the database table
     *
     * @param array $columns
     */
    public function select(array $columns);

    /**
     * Get the results for the model
     *
     * @return mixed
     */
    public function results();

}