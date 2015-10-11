<?php

namespace Michaeljennings\Carpenter\Tests;

class CarpenterTest extends TestCase
{
    public function testAddMethodAcceptsClosure()
    {
        $carpenter = $this->makeCarpenter();

        $this->assertNull($carpenter->add('test', function ($table) {
            $table->setTitle('test');
        }));
    }

    public function testAddMethodAcceptsString()
    {
        $carpenter = $this->makeCarpenter();

        $this->assertNull($carpenter->add('test', 'Michaeljennings\Carpenter\Tests\ExampleTable'));
    }

    public function testTableCanBeBuiltFromString()
    {
        $carpenter = $this->makeCarpenter();

        $this->assertNull($carpenter->add('test', 'Michaeljennings\Carpenter\Tests\ExampleTable'));

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $carpenter->get('test'));
    }

    public function testTableCanBeBuiltFromStringWithCustomMethod()
    {
        $carpenter = $this->makeCarpenter();

        $this->assertNull($carpenter->add('test', 'Michaeljennings\Carpenter\Tests\ExampleTable@build'));

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $carpenter->get('test'));
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\CarpenterCollectionException
     */
    public function testGetThrowsErrorIfTableDoesNotExist()
    {
        $carpenter = $this->makeCarpenter();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $carpenter->get('test'));
    }

    public function testGetMethodReturnsTableInstance()
    {
        $carpenter = $this->makeCarpenter();

        $carpenter->add('test', function ($table) {
            $table->setTitle('test');
        });

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $carpenter->get('test'));
    }

    public function testGetMethodAcceptsAClosureToBeRunOnTheTable()
    {
        $carpenter = $this->makeCarpenter();

        $this->assertNull($carpenter->add('test', function ($table) {
            $table->setTitle('test');
        }));

        $table = $carpenter->get('test', function($table) {
            $table->setTitle('foo');
        });

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $table);
        $this->assertEquals('foo', $table->getTitle());
    }

    public function testMakeMethodReturnsTableInstance()
    {
        $carpenter = $this->makeCarpenter();

        $table = $carpenter->make('test', function ($table) {
            $table->setTitle('test');
        });

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $table);
    }

    public function testManagerExtensionsCanBeSet()
    {
        $carpenter = $this->makeCarpenter();

        $carpenter->extend('store', 'test', function() {
            return new ExampleStore();
        });

        $carpenter->extend('store', 'testStore', 'Michaeljennings\Carpenter\Tests\ExampleStore');

        $table = $carpenter->make('test', function ($table) {
            $table->setTitle('test');
        });

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $table->store('test'));
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $table->store('testStore'));
    }
}