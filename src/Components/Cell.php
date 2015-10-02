<?php

namespace Michaeljennings\Carpenter\Components;

use Michaeljennings\Carpenter\Contracts\Cell as CellContract;
use Michaeljennings\Carpenter\Contracts\Column as ColumnContract;

class Cell implements CellContract
{
    /**
     * The cell value.
     *
     * @var string
     */
    public $value;

    /**
     * The row the cell belongs to.
     *
     * @var string
     */
    protected $row;

    /**
     * The key from the key value pair.
     *
     * @var string|bool
     */
    protected $key;

    /**
     * The column the cell is in.
     *
     * @var Column
     */
    protected $column;

    public function __construct(ColumnContract $column, $value = null, $row = null)
    {
        $this->row = $row;
        $this->column = $column;

        if ($column->hasPresenter()) {
            $callback = $column->getPresenter();
            $value = $callback($value, $row);
        }

        $this->value = $value;
    }

    /**
     * Get the cell value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Alias for the get value method.
     *
     * @return string
     */
    public function value()
    {
        return $this->getValue();
    }

    /**
     * When converted to a string, return the cell value.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }
}