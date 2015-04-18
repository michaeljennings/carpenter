<?php namespace Michaeljennings\Carpenter\Contracts;

use Closure;

interface Action {

    /**
     * Set the column used by the action
     *
     * @param  string|boolean $column
     * @return \Michaeljennings\Carpenter\Components\Action
     */
    public function setColumn($column = false);

    /**
     * Alias for the setColumn method.
     *
     * @param string|bool $column
     * @return \Michaeljennings\Carpenter\Components\Action
     */
    public function column($column = false);

    /**
     * Return the action's column
     *
     * @return string
     */
    public function getColumn();

    /**
     * Set the row used by the action.
     *
     * @param $row
     * @return \Michaeljennings\Carpenter\Components\Action
     */
    public function setRow($row = false);

    /**
     * Alias for the setRow method.
     *
     * @param string|bool $row
     * @return \Michaeljennings\Carpenter\Components\Action
     */
    public function row($row = false);

    /**
     * Add a callback to be run to validate that this action is to be used
     * for the current row.
     *
     * @param callable $callback
     * @return \Michaeljennings\Carpenter\Components\Action
     */
    public function when(Closure $callback);

    /**
     * Check that the current row passes all of the when callbacks.
     *
     * @param $row
     * @return bool
     */
    public function valid($row);

    /**
     * Set the presenter callback for the action.
     *
     * @param  Closure $callback
     * @return \Michaeljennings\Carpenter\Components\Action
     */
    public function setPresenter(Closure $callback);

    /**
     * Alias for the setPresenter method.
     *
     * @param callable $callback
     * @return \Michaeljennings\Carpenter\Components\Action
     */
    public function presenter(Closure $callback);

    /**
     * Return the presenter callback
     *
     * @return closure
     */
    public function getPresenter();

    /**
     * Render the html for the action
     *
     * @return string
     */
    public function render();

    /**
     * Set the class of the action
     *
     * @param string $class
     * @return \Michaeljennings\Carpenter\Components\Action
     */
    public function setClass($class);

    /**
     * Set the label for an action
     *
     * @param string $label
     * @return \Michaeljennings\Carpenter\Components\Action
     */
    public function setLabel($label);

}