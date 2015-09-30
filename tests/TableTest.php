<?php

namespace Michaeljennings\Carpenter\Tests;

use Michaeljennings\Carpenter\Carpenter;

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
}