<?php namespace Michaeljennings\Carpenter\Contracts;

interface Cell {

    /**
     * Check if this cell is a spreadsheet cell and then render it as necessary.
     *
     * @return string
     */
    public function renderSpreadsheetCell();

}