<?php

namespace Michaeljennings\Carpenter\Contracts;

interface Row
{
    /**
     * Set the row id.
     *
     * @param $id
     * @return $this
     */
    public function setId($id);

    /**
     * Alias for setId.
     *
     * @param $id
     * @return Row
     */
    public function id($id);

    /**
     * Get the row id.
     *
     * @return string
     */
    public function getId();

    /**
     * Add a cell to the row.
     *
     * @param string $key
     * @param Cell   $cell
     * @return \Michaeljennings\Carpenter\Components\Row
     */
    public function addCell($key, Cell $cell);

    /**
     * Alias for the addCell method.
     *
     * @param string $key
     * @param Cell   $cell
     * @return \Michaeljennings\Carpenter\Components\Row
     */
    public function cell($key, Cell $cell);

    /**
     * Add a new action to the row.
     *
     * @param Action $action
     * @return \Michaeljennings\Carpenter\Components\Row
     */
    public function addAction(Action $action);

    /**
     * Alias for the addAction method.
     *
     * @param Action $action
     * @return \Michaeljennings\Carpenter\Components\Row
     */
    public function action(Action $action);

    /**
     * Return the row cells.
     *
     * @return array
     */
    public function getCells();

    /**
     * Alias for the getCells method.
     *
     * @return array
     */
    public function cells();

    /**
     * Check if the row has any cells.
     *
     * @return bool
     */
    public function hasCells();

    /**
     * Return the row actions.
     *
     * @return array
     */
    public function getActions();

    /**
     * Alias for the row actions.
     *
     * @return array
     */
    public function actions();

    /**
     * Check if the row has any actions.
     *
     * @return bool
     */
    public function hasActions();
}