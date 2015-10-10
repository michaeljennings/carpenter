<?php

namespace Michaeljennings\Carpenter\Tests\Nexus;

use Michaeljennings\Carpenter\Nexus\Container;
use Michaeljennings\Carpenter\Tests\TestCase;

class ContainerTest extends TestCase
{
    public function testContainerImplementsInterfaces()
    {
        $container = $this->makeContainer();

        $this->assertInstanceOf('ArrayAccess', $container);
        $this->assertInstanceOf('IteratorAggregate', $container);
    }

    public function testIterationReturnsItems()
    {
        $container = $this->makeContainer();

        foreach ($container as $item) {
            $this->assertInstanceOf('Michaeljennings\Carpenter\Nexus\MockArray', $item);
            break;
        }
    }

    public function testContainerActsLikeArray()
    {
        $container = $this->makeContainer();

        $this->assertTrue(isset($container[0]));
        $this->assertFalse(isset($container[5]));

        $container['test'] = 'hello world';

        $this->assertEquals('hello world', $container['test']);

        $this->assertTrue(isset($container['test']));

        unset($container['test']);

        $this->assertFalse(isset($container['test']));
    }

    protected function makeContainer()
    {
        return new Container($this->getData(), $this->getConfig(), 'Michaeljennings\Carpenter\Wrappers\ArrayWrapper');
    }
}