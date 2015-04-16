<?php namespace Michaeljennings\Carpenter\Components;

use Michaeljennings\Carpenter\Contracts\Cell;
use Michaeljennings\Carpenter\Contracts\Action;
use Michaeljennings\Carpenter\Contracts\Row as RowContract;

class Row implements RowContract {

    /**
     * The id of the row
     *
     * @var string
     */
    public $id;

    /**
     * The row cells
     *
     * @var string
     */
    protected $cells = [];

    /**
     * Any row actions.
     *
     * @var array
     */
    protected $actions = [];

    /**
     * Set the row id.
     *
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Alias for setId.
     *
     * @param $id
     * @return Row
     */
    public function id($id)
    {
        return $this->setId($id);
    }

    /**
     * Get the row id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add a cell to the row.
     *
     * @param string $key
     * @param Cell $cell
     * @return $this
     */
    public function addCell($key, Cell $cell)
    {
        $this->cells[$key] = $cell;

        return $this;
    }

    /**
     * Alias for the addCell method.
     *
     * @param string $key
     * @param Cell $cell
     * @return Row
     */
    public function cell($key, Cell $cell)
    {
        return $this->addCell($key, $cell);
    }

    /**
     * Add a new action to the row.
     *
     * @param Action $action
     * @return $this
     */
    public function addAction(Action $action)
    {
        $this->actions[] = $action;

        return $this;
    }

    /**
     * Alias for the addAction method.
     *
     * @param Action $action
     * @return Row
     */
    public function action(Action $action)
    {
        return $this->addAction($action);
    }

    /**
     * Return the row cells.
     *
     * @return array
     */
    public function getCells()
    {
        return $this->cells;
    }

    /**
     * Alias for the getCells method.
     *
     * @return array
     */
    public function cells()
    {
        return $this->getCells();
    }

    /**
     * Check if the row has any cells.
     *
     * @return bool
     */
    public function hasCells()
    {
        return ! empty($this->cells);
    }

    /**
     * Return the row actions.
     *
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Alias for the row actions.
     *
     * @return array
     */
    public function actions()
    {
        return $this->getActions();
    }

    /**
     * Check if the row has any actions.
     *
     * @return bool
     */
    public function hasActions()
    {
        return ! empty($this->actions);
    }

}