<?php

namespace Michaeljennings\Carpenter\Tests;

use Michaeljennings\Carpenter\Session\SessionManager;

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

    protected function makeSessionManager()
    {
        return new SessionManager($this->getConfig());
    }
}