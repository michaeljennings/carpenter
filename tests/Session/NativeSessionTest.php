<?php

namespace Michaeljennings\Carpenter\Tests\Session;

use Michaeljennings\Carpenter\Session\NativeDriver;
use Michaeljennings\Carpenter\Tests\TestCase;

class NativeSessionTest extends TestCase
{
    public function testImplementsSessionContract()
    {
        $session = $this->makeNativeSession();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Session', $session);
    }

    public function testDataCanBeStoredInTheSession()
    {
        $session = $this->makeNativeSession();

        $this->assertEquals('bar', $session->put('foo', 'bar'));
    }

    public function testDataCanBeRetrievedFromTheSession()
    {
        $session = $this->makeNativeSession();

        $this->assertEquals('bar', $session->put('foo', 'bar'));
        $this->assertEquals('bar', $session->get('foo'));
    }

    public function testDataCanBeRemovedFromTheSession()
    {
        $session = $this->makeNativeSession();

        $session->put('foo', 'bar');

        $this->assertEquals('bar', $session->get('foo'));

        $session->forget('foo');

        $this->assertFalse($session->get('foo'));
    }

    public function testHasChecksIfDataInSession()
    {
        $session = $this->makeNativeSession();

        $session->put('foo', 'bar');

        $this->assertTrue($session->has('foo'));

        $session->forget('foo');

        $this->assertFalse($session->has('foo'));
    }

    public function testFlashStoresItemInSession()
    {
        $session = $this->makeNativeSession();

        $this->assertNull($session->flash('foo', 'bar'));
        $this->assertEquals('bar', $session->get('foo'));
    }

    public function testFlashItemsCanBeRemoved()
    {
        $session = $this->makeNativeSession();

        $this->assertNull($session->flash('foo', 'bar'));
        $this->assertEquals('bar', $session->get('foo'));

        $this->assertNull($session->forget('foo'));
        $this->assertFalse($session->get('foo'));
    }

    protected function makeNativeSession()
    {
        return new NativeDriver($this->getConfig());
    }
}