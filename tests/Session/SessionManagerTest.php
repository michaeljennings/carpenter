<?php

namespace Michaeljennings\Carpenter\Tests\Session;

use Michaeljennings\Carpenter\Session\SessionManager;
use Michaeljennings\Carpenter\Tests\TestCase;

class SessionManagerTest extends TestCase
{
    public function testSessionManagerExtendsCoreManager()
    {
        $manager = $this->makeSessionManager();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Nexus\Manager', $manager);
    }

    public function testGetDefaultReturnsDefaultDriver()
    {
        $manager = $this->makeSessionManager();

        $this->assertInternalType('string', $manager->getDefaultDriver());
        $this->assertEquals('native', $manager->getDefaultDriver());
    }

    public function testCallingMethodsOnManagerReturnsDefaultDriver()
    {
        $manager = $this->makeSessionManager();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Session\NativeDriver', $manager->driver());
    }

    public function testDriverCanBeSpecified()
    {
        $manager = $this->makeSessionManager();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Session\NativeDriver', $manager->driver('native'));
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\DriverNotFoundException
     */
    public function testErrorIsThrownIfDriverDoesNotExist()
    {
        $manager = $this->makeSessionManager();

        $manager->driver('foo');
    }

    protected function makeSessionManager()
    {
        return new SessionManager($this->getConfig());
    }
}