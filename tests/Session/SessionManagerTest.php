<?php

namespace Michaeljennings\Carpenter\Tests\Session {

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

            $this->assertInstanceOf('Michaeljennings\Carpenter\Session\Native', $manager->driver());
        }

        public function testCodeigniterDriverCanBeReturned()
        {
            $manager = $this->makeSessionManager();

            $this->assertInstanceOf('Michaeljennings\Carpenter\Session\Codeigniter', $manager->driver('codeigniter'));
        }

        public function testNativeDriverCanBeReturned()
        {
            $manager = $this->makeSessionManager();

            $this->assertInstanceOf('Michaeljennings\Carpenter\Session\Native', $manager->driver('native'));
        }

        public function testIlluminateDriverCanBeReturned()
        {
            $manager = $this->makeSessionManager();

            $this->assertInstanceOf('Michaeljennings\Carpenter\Session\Illuminate', $manager->driver('illuminate'));
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
            return new SessionManager($this->getConfig()['session']);
        }
    }
}

namespace Michaeljennings\Carpenter\Session {

    use Illuminate\Session\Store;
    use Symfony\Component\HttpFoundation\Session\Storage\Handler\NullSessionHandler;

    function app()
    {
        return new IlluminateManager();
    }

    class IlluminateManager
    {
        public function driver()
        {
            return new Store('coreplex.session', new NullSessionHandler());
        }
    }

}