<?php

namespace Michaeljennings\Carpenter\Tests\Session;

use Illuminate\Session\Store;
use Michaeljennings\Carpenter\Session\IlluminateDriver;
use Michaeljennings\Carpenter\Tests\TestCase;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NullSessionHandler;

class IlluminateSessionTest extends TestCase
{
    public function testImplementsSessionContract()
    {
        $session = $this->makeSession();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Session', $session);
    }

    public function testDataCanBeStoredInTheSession()
    {
        $session = $this->makeSession();

        $this->assertNull($session->put('foo', 'bar'));
    }

    public function testDataCanBeRetrievedFromTheSession()
    {
        $session = $this->makeSession();

        $this->assertNull($session->put('foo', 'bar'));
        $this->assertEquals('bar', $session->get('foo'));
    }

    public function testDataCanBeRemovedFromTheSession()
    {
        $session = $this->makeSession();

        $session->put('foo', 'bar');

        $this->assertEquals('bar', $session->get('foo'));

        $session->forget('foo');

        $this->assertNull($session->get('foo'));
    }

    public function testHasChecksIfDataInSession()
    {
        $session = $this->makeSession();

        $session->put('foo', 'bar');

        $this->assertTrue($session->has('foo'));

        $session->forget('foo');

        $this->assertFalse($session->has('foo'));
    }

    public function testFlashStoresItemInSession()
    {
        $session = $this->makeSession();

        $this->assertNull($session->flash('foo', 'bar'));
        $this->assertEquals('bar', $session->get('foo'));
    }

    public function testNonSpecifiedMethodsCanStillBeCalled()
    {
        $session = $this->makeSession();

        $session->put('foo', 'bar');

        $this->assertEquals('bar', $session->get('foo'));

        $session->flush();

        $this->assertNull($session->get('foo'));
    }

    protected function makeSession()
    {
        return new IlluminateDriver(new Store($this->getConfig()['session']['key'], new NullSessionHandler()));
    }
}