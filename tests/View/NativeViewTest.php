<?php

namespace Michaeljennings\Carpenter\Tests\View;

use Michaeljennings\Carpenter\Tests\TestCase;
use Michaeljennings\Carpenter\View\Native;

class NativeViewTest extends TestCase
{
    public function testViewImplementsContract()
    {
        $view = $this->makeNativeView();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\View', $view);
    }

    public function testViewRendersToString()
    {
        $view = $this->makeNativeView();
        $string = $view->make(__DIR__ . '/test-view.php');

        $this->assertInternalType('string', $string);
        $this->assertContains('hello world', $string);
    }

    public function testDataCanBePassedToView()
    {
        $view = $this->makeNativeView();
        $string = $view->make(__DIR__ . '/test-view.php', ['test' => 'foo']);

        $this->assertInternalType('string', $string);
        $this->assertContains('foo', $string);
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\ViewNotFoundException
     */
    public function testExceptionThrownIfViewNotFound()
    {
        $view = $this->makeNativeView();
        $view->make(__DIR__ . '/not-existent-view.php');
    }

    protected function makeNativeView()
    {
        return new Native();
    }
}