<?php

namespace Michaeljennings\Carpenter\Contracts;

use Closure;

interface Action
{
    /**
     * Set the column used by the action
     *
     * @param  string|boolean $column
     * @return \Michaeljennings\Carpenter\Contracts\Action
     */
    public function setColumn($column = false);

    /**
     * Alias for the setColumn method.
     *
     * @param string|bool $column
     * @return \Michaeljennings\Carpenter\Contracts\Action
     */
    public function column($column = false);

    /**
     * Return the action's column
     *
     * @return string
     */
    public function getColumn();

    /**
     * Set the value used by the action.
     *
     * @param string|bool $value
     * @return \Michaeljennings\Carpenter\Contracts\Action
     */
    public function setValue($value = false);

    /**
     * Alias for the setValue method.
     *
     * @param string|bool $value
     * @return \Michaeljennings\Carpenter\Contracts\Action
     */
    public function value($value = false);

    /**
     * Set the row used by the action.
     *
     * @param $row
     * @return \Michaeljennings\Carpenter\Contracts\Action
     */
    public function setRow($row = false);

    /**
     * Alias for the setRow method.
     *
     * @param string|bool $row
     * @return \Michaeljennings\Carpenter\Contracts\Action
     */
    public function row($row = false);

    /**
     * Returns the current row set on the action
     *
     * @return mixed
     */
    public function getRow();

    /**
     * Add a callback to be run to validate that this action is to be used
     * for the current row.
     *
     * @param callable $callback
     * @return \Michaeljennings\Carpenter\Contracts\Action
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
     * @return \Michaeljennings\Carpenter\Contracts\Action
     */
    public function setPresenter(Closure $callback);

    /**
     * Alias for the setPresenter method.
     *
     * @param callable $callback
     * @return \Michaeljennings\Carpenter\Contracts\Action
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
     * Set the label for an action
     *
     * @param string|Closure $label
     * @return \Michaeljennings\Carpenter\Contracts\Action
     */
    public function setLabel($label);

    /**
     * Set the HTML tag to wrap the action with.
     *
     * @param string $tag
     * @return $this
     */
    public function setTag($tag);

    /**
     * Set the href for the anchor and set the action tag to an anchor.
     *
     * @param  string|\Closure $href
     * @return $this
     */
    public function setHref($href);

    /**
     * Alias for setHref method.
     *
     * @param  string|\Closure $href
     * @return $this
     */
    public function href($href);

    /**
     * Set the class of the action
     *
     * @param string|\Closure $class
     * @return $this
     */
    public function setClass($class);

    /**
     * Set the provided attribute for the action.
     *
     * @param string          $attribute
     * @param string|\Closure $value
     * @return $this
     */
    public function setAttribute($attribute, $value);
}