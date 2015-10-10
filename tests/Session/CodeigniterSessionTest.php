<?php

namespace Michaeljennings\Carpenter\Tests\Session {

    use Michaeljennings\Carpenter\Session\Codeigniter;
    use Michaeljennings\Carpenter\Tests\TestCase;

    class CodeigniterSessionTest extends TestCase
    {
        public function testImplementsSessionContract()
        {
            $session = $this->makeSession();

            $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Session', $session);
        }

        public function testDataCanBeStoredInTheSession()
        {
            $session = $this->makeSession();

            $this->assertTrue($session->put('foo', 'bar'));
        }

        public function testDataCanBeRetrievedFromTheSession()
        {
            $session = $this->makeSession();

            $this->assertTrue($session->put('foo', 'bar'));
            $this->assertEquals('bar', $session->get('foo'));
        }

        public function testDataCanBeRemovedFromTheSession()
        {
            $session = $this->makeSession();

            $session->put('foo', 'bar');

            $this->assertEquals('bar', $session->get('foo'));

            $this->assertTrue($session->forget('foo'));
        }

        public function testHasChecksIfDataInSession()
        {
            $session = $this->makeSession();

            $session->put('foo', 'bar');

            $this->assertTrue($session->has('foo'));
        }

        public function testFlashStoresItemInSession()
        {
            $session = $this->makeSession();

            $this->assertTrue($session->flash('foo', 'bar'));
            $this->assertEquals('bar', $session->get('foo'));
        }

        protected function makeSession()
        {
            return new Codeigniter();
        }
    }
}

namespace Michaeljennings\Carpenter\Session {
    use Mockery as m;

    function get_instance()
    {
        $object = new \stdClass();

        $object->session = m::mock('Session', [
            'userdata' => 'bar',
            'set_userdata' => true,
            'set_flashdata' => true,
            'unset_userdata' => true,
        ]);

        return $object;
    }
}