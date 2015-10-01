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

    protected function makeContainer()
    {
        return new Container($this->getData(), $this->getConfig(), 'Michaeljennings\Carpenter\Wrappers\ArrayWrapper');
    }
}