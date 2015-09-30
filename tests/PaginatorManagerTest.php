<?php

namespace Michaeljennings\Carpenter\Tests;

use Michaeljennings\Carpenter\Pagination\PaginationManager;

class PaginatorManagerTest extends TestCase
{
    public function testManagerExtendsCoreManager()
    {
        $manager = $this->makePaginatorManager();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Nexus\Manager', $manager);
    }

    public function testGetDefaultReturnsDefaultDriver()
    {
        $manager = $this->makePaginatorManager();

        $this->assertInternalType('string', $manager->getDefaultDriver());
        $this->assertEquals('native', $manager->getDefaultDriver());
    }

    public function testCallingMethodsOnManagerReturnsDefaultDriver()
    {
        $manager = $this->makePaginatorManager();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Pagination\Native', $manager->driver());
    }

    public function testDriverCanBeSpecified()
    {
        $manager = $this->makePaginatorManager();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Pagination\Native', $manager->driver('native'));
    }

    protected function makePaginatorManager()
    {
        return new PaginationManager($this->getConfig());
    }
}