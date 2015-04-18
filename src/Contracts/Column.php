<?php namespace Michaeljennings\Carpenter\Contracts;

use Closure;

interface Column {

    /**
     * Set the presenter callback for the column cells
     *
     * @param  Closure $callback
     * @return \Michaeljennings\Carpenter\Components\Column
     */
    public function setPresenter(Closure $callback);

    /**
     * Alias for the setPresenter method.
     *
     * @param callable $callback
     * @return \Michaeljennings\Carpenter\Components\Column
     */
    public function presenter(Closure $callback);

    /**
     * Check if there is a presenter callback for the column
     * @return boolean
     */
    public function hasPresenter();

    /**
     * Return the column presenter.
     *
     * @return Closure|boolean
     */
    public function getPresenter();

    /**
     * Accessor for the columns href
     *
     * @return string
     */
    public function getHref();

    /**
     * Set the label for the column.
     *
     * @param $label
     * @return \Michaeljennings\Carpenter\Components\Column
     */
    public function setLabel($label);

    /**
     * Get the label for the column.
     *
     * @return string
     */
    public function getLabel();

    /**
     * Set this column as sortable.
     *
     * @return \Michaeljennings\Carpenter\Components\Column
     */
    public function sortable();

    /**
     * Set this column as unsortable.
     *
     * @return \Michaeljennings\Carpenter\Components\Column
     */
    public function unsortable();

    /**
     * Return whether the column is sortable or not.
     *
     * @return bool
     */
    public function isSortable();

}