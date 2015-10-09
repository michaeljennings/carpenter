<?php

namespace Michaeljennings\Carpenter\Tests;

use Illuminate\Container\Container;
use Michaeljennings\Carpenter\CarpenterServiceProvider;
use Mockery as m;

class ServiceProviderTest extends TestCase
{
    protected $app;

    protected $serviceProvider;

    public function setUp()
    {
        $this->app = new Container();
        $this->app['config'] = ['carpenter' => $this->getConfig()];

        $this->serviceProvider = new CarpenterServiceProvider($this->app);
    }

    public function testProvidesReturnsAllOfTheProvidedServices()
    {
        $this->assertContains('michaeljennings.carpenter', $this->serviceProvider->provides());
        $this->assertContains('Michaeljennings\Carpenter\Contracts\Carpenter', $this->serviceProvider->provides());
    }

    public function testCarpenterCanBeResolvedFromTheContainer()
    {
        $this->serviceProvider->register();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Carpenter', $this->app->make('Michaeljennings\Carpenter\Contracts\Carpenter'));
        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Carpenter', $this->app->make('michaeljennings.carpenter'));
    }
}