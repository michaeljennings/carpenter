<?php

namespace Michaeljennings\Carpenter\Tests;

class TableTest extends TestCase
{
    public function testCanAddAndGetTitle()
    {
        $carpenter = $this->makeCarpenter();

        $carpenter->add('test', function ($table) {
            $table->setTitle('test');
        });

        $this->assertEquals('test', $carpenter->get('test')->getTitle());
    }

    public function testCanAddAndGetActions()
    {
        $carpenter = $this->makeCarpenter();

        $carpenter->add('test', function ($table) {
            $table->action('create');
        });

        $this->assertEquals(1, count($carpenter->get('test')->actions()));
    }

    public function testActionsCanBeAddedAfterTheTableHasBeResolved()
    {
        $carpenter = $this->makeCarpenter();

        $table = $carpenter->make('test', function ($table) {
            $table->action('create');
        });

        $this->assertEquals(1, count($table->actions()));

        $table->action('edit');

        $this->assertEquals(2, count($table->actions()));
    }

    public function testColumnsCanBeAddedAndRetrieved()
    {
        $table = $this->makeTable();

        $table->column('test');

        $this->assertEquals(1, count($table->columns()));
    }

    public function testColumnsCanBeAddedAfterTheTableIsResolved()
    {
        $carpenter = $this->makeCarpenter();

        $table = $carpenter->make('test', function ($table) {
            $table->column('test');
        });

        $this->assertEquals(1, count($table->columns()));

        $table->column('foo');

        $this->assertEquals(2, count($table->columns()));
    }

    public function testColumnsCanBeEditedAfterTheTableIsResolved()
    {
        $table = $this->makeTable();

        $column = $table->column('test')->setLabel('Foo');

        $this->assertContains('Foo', $column->getLabel());

        $column->setLabel('Bar');

        $this->assertContains('Bar', $column->getLabel());
    }
}