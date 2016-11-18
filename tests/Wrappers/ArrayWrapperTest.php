<?php

namespace Michaeljennings\Carpenter\Tests\Wrappers;

use Michaeljennings\Carpenter\Tests\TestCase;
use Michaeljennings\Carpenter\Wrappers\ArrayWrapper;

class ArrayWrapperTest extends TestCase
{
    public function testWrapperImplementsContracts()
    {
        $wrapper = $this->makeWrapper();

        $this->assertInstanceOf('ArrayAccess', $wrapper);
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Wrapper', $wrapper);
    }

    public function testWrapperCanBeMadeFromObject()
    {
        $wrapper = new ArrayWrapper($this->getDataAsObjects()[0]);

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Wrapper', $wrapper);
        $this->assertTrue(isset($wrapper->foo));
        $this->assertTrue(isset($wrapper['foo']));
    }

    public function testDataCanBeRetrieved()
    {
        $wrapper = $this->makeWrapper();

        $this->assertEquals('Test', $wrapper->test);
        $this->assertEquals('Test 1', $wrapper['foo']);
    }

    public function testDataCanBeSet()
    {
        $wrapper = $this->makeWrapper();

        $wrapper->test = 'testing';
        $wrapper['bar'] = 'foo';

        $this->assertEquals('testing', $wrapper->test);
        $this->assertEquals('foo', $wrapper->bar);
    }

    public function testDataCanBeCheckedIfItIsSet()
    {
        $wrapper = $this->makeWrapper();

        $this->assertTrue(isset($wrapper->foo));
        $this->assertFalse(isset($wrapper->notSet));
        $this->assertTrue(isset($wrapper['foo']));
        $this->assertFalse(isset($wrapper['notSet']));
    }

    public function testDataCanBeUnset()
    {
        $wrapper = $this->makeWrapper();

        $this->assertTrue(isset($wrapper->foo));
        $this->assertTrue(isset($wrapper['test']));

        unset($wrapper->foo);
        unset($wrapper['test']);

        $this->assertFalse(isset($wrapper->foo));
        $this->assertFalse(isset($wrapper['test']));
    }

    public function testItemCanBeRetrieved()
    {
        $wrapper = $this->makeWrapper();

        $this->assertEquals($wrapper->getItem(), $this->getData()[0]);
    }

    protected function makeWrapper()
    {
        return new ArrayWrapper($this->getData()[0]);
    }
}