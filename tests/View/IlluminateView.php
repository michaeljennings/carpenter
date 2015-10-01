<?php

namespace Michaeljennings\Carpenter\Tests\View;

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Michaeljennings\Carpenter\Tests\TestCase;
use Michaeljennings\Carpenter\View\IlluminateDriver;

class IlluminateView extends TestCase
{
    public function testViewImplementsContract()
    {
        $view = $this->makeView();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\View', $view);
    }

    public function testViewRendersToString()
    {
        $view = $this->makeView();
        $string = $view->make('test-view');

        $this->assertInternalType('string', $string);
        $this->assertContains('hello world', $string);
    }

    public function testDataCanBePassedToView()
    {
        $view = $this->makeView();
        $string = $view->make('test-view', ['test' => 'foo']);

        $this->assertInternalType('string', $string);
        $this->assertContains('foo', $string);
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\ViewNotFoundException
     */
    public function testExceptionThrownIfViewNotFound()
    {
        $view = $this->makeView();
        $view->make('not-existent-view');
    }

    protected function makeView()
    {
        $app = new Container();

        $resolver = new EngineResolver;
        $resolver->register('php', function () { return new PhpEngine; });

        $finder = new FileViewFinder(new Filesystem, [realpath(__DIR__)]);

        $dispatcher = (new Dispatcher($app))->setQueueResolver(function () use ($app) {
            return $app->make('Illuminate\Contracts\Queue\Factory');
        });

        $env = new Factory($resolver, $finder, $dispatcher);
        $env->setContainer($app);
        $env->share('app', $app);

        return new IlluminateDriver($env);
    }
}