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
     * Return all of the table's columns.
     *
     * @return array
     */
    public function columns();

    /**
     * Alias of the columns method.
     *
     * @return array
     */
    public function getColumns();

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
    public function actions();

    /**
     * Alias for the actions method.
     *
     * @return array
     */
    public function getActions();

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
    public function template($template);

    /**
     * Alias for the template method.
     *
     * @param string $template
     * @return \Michaeljennings\Carpenter\Table
     */
    public function setTemplate($template);

    /**
     * Set the table title.
     *
     * @param string $title
     * @return \Michaeljennings\Carpenter\Table
     */
    public function title($title);

    /**
     * Alias for the title method.
     *
     * @param string $title
     * @return \Michaeljennings\Carpenter\Table
     */
    public function setTitle($title);

    /**
     * Return the table title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set the url the table actions post to
     *
     * @param string $action
     * @return \Michaeljennings\Carpenter\Table
     */
    public function formAction($action);

    /**
     * Alias for the form action method.
     *
     * @param string $action
     * @return \Michaeljennings\Carpenter\Table
     */
    public function setFormAction($action);

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
     * @return $this
     */
    public function formMethod($method);

    /**
     * Alias for the formMethod method.
     *
     * @param $method
     * @return Table
     */
    public function setFormMethod($method);

    /**
     * Return the form method.
     *
     * @return string
     */
    public function getFormMethod();

    /**
     * Get the table links
     *
     * @return string
     */
    public function links();

    /**
     * Alias for the links method.
     *
     * @return string
     */
    public function getLinks();

    /**
     * Check if there are any table links.
     *
     * @return boolean
     */
    public function hasLinks();

    /**
     * Set the results to be displayed.
     *
     * @param $data
     * @return \Michaeljennings\Carpenter\Table
     */
    public function results($data);

    /**
     * Alias of the results method.
     *
     * @param $data
     * @return \Michaeljennings\Carpenter\Table
     */
    public function setResults($data);

    /**
     * Change a driver to another supported driver.
     *
     * @param $type
     * @param $driver
     * @return \Michaeljennings\Carpenter\Table
     */
    public function driver($type, $driver);

    /**
     * Change the store driver.
     *
     * @param $driver
     * @return \Michaeljennings\Carpenter\Table
     */
    public function store($driver);

    /**
     * Change the session driver.
     *
     * @param $driver
     * @return \Michaeljennings\Carpenter\Table
     */
    public function session($driver);

    /**
     * Change the paginator driver.
     *
     * @param $driver
     * @return \Michaeljennings\Carpenter\Table
     */
    public function paginator($driver);

    /**
     * Change the view driver.
     *
     * @param $driver
     * @return \Michaeljennings\Carpenter\Table
     */
    public function view($driver);

}