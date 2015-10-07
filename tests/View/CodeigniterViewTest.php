<?php

namespace Michaeljennings\Carpenter\Tests\View {

    use Michaeljennings\Carpenter\Tests\TestCase;
    use Michaeljennings\Carpenter\View\CodeigniterDriver;

    class CodeigniterViewTest extends TestCase
    {
        public function testViewImplementsContract()
        {
            $view = $this->makeView();

            $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\View', $view);
        }

        public function testMakeReturnsString()
        {
            $view = $this->makeView();

            $this->assertInternalType('string', $view->make(__DIR__ . '/test-view.php'));
        }

        public function testDataCanBePassedToView()
        {
            $view = $this->makeView();
            $string = $view->make(__DIR__ . '/test-view.php', ['test' => 'foo']);

            $this->assertInternalType('string', $string);
            $this->assertContains('foo', $string);
        }

        public function makeView()
        {
            return new CodeigniterDriver();
        }
    }
}

namespace Michaeljennings\Carpenter\View {

    use Mockery as m;

    function get_instance()
    {
        $object = new \stdClass();

        $object->load = new ExampleLoader();

        return $object;
    }

    class ExampleLoader
    {
        public function view($view, $data = [], $render = false)
        {
            return 'hello world ' . implode(' ', $data);
        }
    }

}