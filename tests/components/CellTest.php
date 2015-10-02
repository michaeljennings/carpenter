<?php

namespace Michaeljennings\Carpenter\Tests\Components;

use Michaeljennings\Carpenter\Components\Cell as CellComponent;
use Michaeljennings\Carpenter\Tests\TestCase;

class CellTest extends TestCase
{
    public function testCellImplementsContract()
    {
        $cell = $this->makeCell();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Cell', $cell);
    }

    public function testValueCanBeRetrieved()
    {
        $cell = $this->makeCell();

        $this->assertEquals('Test', $cell->value);
        $this->assertEquals('Test', $cell->getValue());
        $this->assertEquals('Test', $cell->value());
        $this->assertEquals('Test', $cell);
    }

    public function testColumnPresenterIsRunOnCellValue()
    {
        $table = $this->makeTableWithData();

        $table->column('foo')->setPresenter(function($value) {
            return 'Value is: ' . $value;
        });

        $cell = new CellComponent($table->column('foo'), 'Test', $this->getData()[0]);

        $this->assertEquals('Value is: Test', $cell->value);
    }

    protected function makeCell()
    {
        $table = $this->makeTableWithData();

        return new CellComponent($table->column('foo'), 'Test', $this->getData()[0]);
    }
}