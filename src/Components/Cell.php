<?php

namespace Michaeljennings\Carpenter\Components;

use Michaeljennings\Carpenter\Contracts\Cell as CellContract;
use Michaeljennings\Carpenter\Contracts\Column as ColumnContract;

class Cell implements CellContract
{
    /**
     * The cell value
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

    public function __construct($value, $row, ColumnContract $column)
    {
        $this->row = $row;
        $this->column = $column;

        $this->createCell($value, $row, $column);
    }

    /**
     * Run the column presenter on the cell value.
     *
     * @param  string         $value
     * @param  mixed          $row
     * @param  ColumnContract $column
     */
    protected function createCell($value, $row, ColumnContract $column)
    {
        if ($column->hasPresenter()) {
            $callback = $column->getPresenter();
            $value = $callback($value, $row);
        }

        $this->value = $value;
    }

    /**
     * Check if this cell is a spreadsheet cell and then render it as necessary.
     *
     * @return string
     */
    public function renderSpreadsheetCell()
    {
        if ($this->column) {
            if ($this->column->hasSpreadsheetCell()) {
                $cell = new SpreadsheetCell($this->value, $this->row->id);
                $callback = $this->column->getSpreadsheetCell();
                $callback($cell);

                return $cell->render();
            }

            return $this->value;
        }
    }

    /**
     * When converted to a string, return the cell value.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->renderSpreadsheetCell();
    }
}