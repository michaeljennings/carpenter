<?php

namespace Michaeljennings\Carpenter\Tests\Store;

use Michaeljennings\Carpenter\Store\StoreManager;
use Michaeljennings\Carpenter\Tests\TestCase;

class StoreManagerTest extends TestCase
{
    public function testEloquentDriverCanBeSet()
    {
        $manager = $this->makeManager();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Store\EloquentStore', $manager->driver('eloquent'));
    }

    public function testLaravel4DriverCanBeSet()
    {
        $manager = $this->makeManager();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Store\Laravel4\EloquentStore', $manager->driver('laravel4'));
    }

    public function testArrayDriverCanBeSet()
    {
        $manager = $this->makeManager();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Store\ArrayStore', $manager->driver('array'));
    }

    public function testCodeigniterDriverCanBeSet()
    {
        $manager = $this->makeManager();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Store\CodeigniterStore', $manager->driver('codeigniter'));
    }

    public function testDefaultDriverIsReturnedIfNoDriverIsSpecified()
    {
        $manager = $this->makeManager();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Store\ArrayStore', $manager->driver());
    }

    public function makeManager()
    {
        return new StoreManager($this->getConfig());
    }
}