<?php namespace Michaeljennings\Carpenter\Contracts;

use Closure;

interface Table {

    /**
     * Add a new column to the table.
     *
     * @param string $name
     * @return \Michaeljennings\Carpenter\Components\Column
     */
    public function column($name);

    /**
     * Add a new action to the table.
     *
     * @param string $name
     * @param string $position
     * @return \Michaeljennings\Carpenter\Components\Action
     */
    public function action($name, $position = 'table');

    /**
     * Set the amount to paginate the table by.
     *
     * @param string|integer $amount
     * @return \Michaeljennings\Carpenter\Table
     */
    public function paginate($amount);

    /**
     * Set the model to be used by the table. Can be either the model name or
     * an instance of the model.
     *
     * @param mixed $model
     * @return mixed
     */
    public function model($model);

    /**
     * Add a new filter to be run on the results.
     *
     * @param callable $filter
     * @return \Michaeljennings\Carpenter\Table
     */
    public function filter(Closure $filter);

    /**
     * Render the table to a string.
     *
     * @return string
     */
    public function render();

    /**
     * Return all of the table's rows.
     *
     * @return array
     */
    public function rows();

    /**
     * Alias of the rows method.
     *
     * @return array
     */
    public function getRows();

    /**
     * Check if the table has any rows.
     *
     * @return boolean
     */
    public function hasRows();

    /**
     * Get all of the table columns.
     *
     * @return array
     */
    public function getColumns();

    /**
     * Alias of the getColumns method.
     *
     * @return array
     */
    public function columns();

    /**
     * Check if there are any table columns
     *
     * @return boolean
     */
    public function hasColumns();

    /**
     * Get the actions with a position of table.
     *
     * @return array
     */
    public function getActions();

    /**
     * Alias for the actions method.
     *
     * @return array
     */
    public function actions();

    /**
     * Check if there are any table actions.
     *
     * @return boolean
     */
    public function hasActions();

    /**
     * Set the template for this table instance.
     *
     * @param string $template
     * @return \Michaeljennings\Carpenter\Table
     */
    public function setTemplate($template);

    /**
     * Alias for the template method.
     *
     * @param string $template
     * @return \Michaeljennings\Carpenter\Table
     */
    public function template($template);

    /**
     * Set the table title.
     *
     * @param string $title
     * @return \Michaeljennings\Carpenter\Table
     */
    public function setTitle($title);

    /**
     * Alias for the setTitle method.
     *
     * @param string $title
     * @return \Michaeljennings\Carpenter\Table
     */
    public function title($title);

    /**
     * Return the table title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set the url the table actions post to.
     *
     * @param string $action
     * @return \Michaeljennings\Carpenter\Table
     */
    public function setFormAction($action);

    /**
     * Alias for the setFormAction method.
     *
     * @param string $action
     * @return \Michaeljennings\Carpenter\Table
     */
    public function formAction($action);

    /**
     * Return the form action.
     *
     * @return null|string
     */
    public function getFormAction();

    /**
     * Set the method for the table form to use.
     *
     * @param $method
     * @return Table
     */
    public function setFormMethod($method);

    /**
     * Alias for the setFormMethod method.
     *
     * @param $method
     * @return $this
     */
    public function formMethod($method);

    /**
     * Return the form method.
     *
     * @return string
     */
    public function getFormMethod();

    /**
     * Get the table links.
     *
     * @return string
     */
    public function getLinks();

    /**
     * Alias for the getLinks method.
     *
     * @return string
     */
    public function links();

    /**
     * Check if there are any table links.
     *
     * @return boolean
     */
    public function hasLinks();

    /**
     * Set the data to be displayed.
     *
     * @param mixed $data
     * @return \Michaeljennings\Carpenter\Table
     */
    public function data($data);

    /**
     * Change the store driver.
     *
     * @param $driver
     * @return \Michaeljennings\Carpenter\Table
     */
    public function store($driver);

}