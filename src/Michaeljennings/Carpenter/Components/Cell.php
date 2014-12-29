<?php namespace Michaeljennings\Carpenter\Components;

class Cell {

    /**
     * The cell value
     *
     * @var string
     */
    public $value;

    protected $result;

    protected $key;

    protected $column;

    public function __construct($value, $row = null, $key = false, $column = false)
    {
        $this->row = $row;
        $this->key = $key;
        $this->column = $column;

        $this->createCell($value, $row, $column);
    }

    /**
     * Run the column presenter on the cell value
     *
     * @param  string         $value
     * @param  mixed  		  $row
     * @param  Column|boolean $column
     */
    public function createCell($value, $row, $column = false)
    {
        if ($column) {
            if (($column->hasPresenter())) {
                $callback = $column->getPresenter();
                $value = $callback($value, $row);
            }
        }

        $this->value = $value;
    }

    public function renderSpreadsheetCell()
    {
        if ($this->column) {
            if ($this->column->hasSpreadsheetCell()) {
                $cell = new SpreadsheetCell($this->value, $this->row->id);
                $callback = $this->column->getSpreadsheetCell();
                $callback($cell);

                return $cell->render();
            } else {
                return $this->value;
            }
        }
    }
}