<?php

namespace Michaeljennings\Carpenter\Tests\View {

    use Michaeljennings\Carpenter\Tests\TestCase;
    use Michaeljennings\Carpenter\View\ViewManager;

    class ViewManagerTest extends TestCase
    {
        public function testNativeDriverCanBeReturned()
        {
            $manager = $this->makeManager();

            $this->assertInstanceOf('Michaeljennings\Carpenter\View\Native', $manager->driver('native'));
        }

        public function testCodeigniterDriverCanBeReturned()
        {
            $manager = $this->makeManager();

            $this->assertInstanceOf('Michaeljennings\Carpenter\View\Codeigniter',
                $manager->driver('codeigniter'));
        }

        public function testIlluminateDriverCanBeReturned()
        {
            $manager = $this->makeManager();

            $this->assertInstanceOf('Michaeljennings\Carpenter\View\Illuminate', $manager->driver('illuminate'));
        }

        public function testDefaultDriverIsReturnedIfNoneIsSpecified()
        {
            $manager = $this->makeManager();

            $this->assertInstanceOf('Michaeljennings\Carpenter\View\Native', $manager->driver());
        }

        protected function makeManager()
        {
            return new ViewManager($this->getConfig());
        }
    }
}

namespace Michaeljennings\Carpenter\View {

    use Illuminate\Container\Container;
    use Illuminate\Events\Dispatcher;
    use Illuminate\Filesystem\Filesystem;
    use Illuminate\View\Engines\EngineResolver;
    use Illuminate\View\Engines\PhpEngine;
    use Illuminate\View\Factory;
    use Illuminate\View\FileViewFinder;

    function app()
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

        return $env;
    }

}