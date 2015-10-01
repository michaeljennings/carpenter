<?php

namespace Michaeljennings\Carpenter\Tests\Components;

use Michaeljennings\Carpenter\Tests\TestCase;

class ColumnTest extends TestCase
{
    public function testLabelCanBeSetAndRetrieved()
    {
        $table = $this->makeTable();

        $column = $table->column('test')->setLabel('Foo');

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Column', $column);
        $this->assertContains('Foo', $column->getLabel());
    }

    public function testHrefIsCreatedAutomatically()
    {
        $table = $this->makeTable();

        $column = $table->column('test')->setLabel('Foo');

        $this->assertContains('test', $column->getHref());
    }

    public function testCustomSortCanBeSetForColumns()
    {
        $table = $this->makeTable();

        $column = $table->column('test')->sort(function($q) {
            $q->orderBy('foo');
        });

        $this->assertTrue($column->hasSort());
        $this->assertInstanceOf('Closure', $column->getSort());
    }

    public function testColumnCanBeMadeSortable()
    {
        $table = $this->makeTable();

        $column = $table->column('test')->unsortable();

        $this->assertFalse($column->isSortable());

        $column->sortable();

        $this->assertTrue($column->isSortable());
    }
}