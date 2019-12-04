<?php

namespace Michaeljennings\Carpenter\Components;

use Michaeljennings\Carpenter\Contracts\Cell as CellContract;
use Michaeljennings\Carpenter\Contracts\Row as RowContract;
use Michaeljennings\Carpenter\Contracts\Action as ActionContract;

class Row implements RowContract
{
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
     * Any row attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The result
     *
     * @var mixed
     */
    protected $result;

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
     * @param string       $key
     * @param CellContract $cell
     * @return $this
     */
    public function addCell($key, CellContract $cell)
    {
        $this->cells[$key] = $cell;

        return $this;
    }

    /**
     * Alias for the addCell method.
     *
     * @param string       $key
     * @param CellContract $cell
     * @return Row
     */
    public function cell($key, CellContract $cell)
    {
        return $this->addCell($key, $cell);
    }

    /**
     * Add a new action to the row.
     *
     * @param ActionContract $action
     * @return $this
     */
    public function addAction(ActionContract $action)
    {
        $this->actions[] = $action;

        return $this;
    }

    /**
     * Alias for the addAction method.
     *
     * @param ActionContract $action
     * @return Row
     */
    public function action(ActionContract $action)
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

    /**
     * Get the result for the row
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set the result for the row
     *
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * Get all of the component attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set the provided attribute for the row.
     *
     * @param string         $attribute
     * @param string|Closure $value
     * @return $this
     */
    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

    /**
     * Render the element attributes to a string.
     *
     * @param  array $attributes
     * @return string
     */
    public function renderAttributes()
    {
        $renderedAttributes = [];

        foreach ($this->attributes as $attribute => $value) {
            if ($value instanceof Closure) {
                $renderedAttributes[] = $attribute . '="' . $value($this->getId(), $this->getResult()) . '"';
            } else {
                $renderedAttributes[] = $attribute . '="' . $value . '"';
            }
        }

        return implode(' ', $renderedAttributes);
    }


    /**
     * Add a class to the current row
     *
     * @param string $class
     * @return $this
     */
    public function addClass($class)
    {
        if (empty($this->attributes['class'])) {
            $this->attributes['class'] = $class;

            return $this;
        }

        // Append the new class(es) on the end of the current ones
        $this->attributes['class'] = $this->attributes['class'] . ' ' . $class;

        return $this;
    }
}