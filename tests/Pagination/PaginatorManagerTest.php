<?php

namespace Michaeljennings\Carpenter\Tests\Pagination {

    use Michaeljennings\Carpenter\Pagination\PaginationManager;
    use Michaeljennings\Carpenter\Tests\TestCase;

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

        public function testNativeDriverCanBeReturned()
        {
            $manager = $this->makePaginatorManager();

            $this->assertInstanceOf('Michaeljennings\Carpenter\Pagination\Native', $manager->driver('native'));
        }

        public function testIlluminateDriverCanBeReturned()
        {
            $manager = $this->makePaginatorManager();

            $this->assertInstanceOf('Michaeljennings\Carpenter\Pagination\IlluminateDriver', $manager->driver('illuminate'));
        }

        public function testLaravel4DriverCanBeReturned()
        {
            $manager = $this->makePaginatorManager();

            $this->assertInstanceOf('Michaeljennings\Carpenter\Pagination\Laravel4\IlluminateDriver', $manager->driver('laravel4'));
        }

        /**
         * @expectedException \Michaeljennings\Carpenter\Exceptions\DriverNotFoundException
         */
        public function testErrorIsThrownIfDriverDoesNotExist()
        {
            $manager = $this->makePaginatorManager();

            $manager->driver('foo');
        }

        protected function makePaginatorManager()
        {
            return new PaginationManager($this->getConfig());
        }
    }
}

namespace Michaeljennings\Carpenter\Pagination {

    function app()
    {
        return null;
    }

}