<?php

namespace Michaeljennings\Carpenter\Tests\Wrappers;

use Michaeljennings\Carpenter\Tests\TestCase;
use Michaeljennings\Carpenter\Wrappers\ArrayWrapper;

class ArrayWrapperTest extends TestCase
{
    public function testDataCanBeAccessedDynamically()
    {
        $wrapper = $this->makeWrapper();

        $this->assertEquals('Test', $wrapper->test);
        $this->assertEquals('Test 1', $wrapper->foo);
    }

    protected function makeWrapper()
    {
        return new ArrayWrapper($this->getData()[0]);
    }
}