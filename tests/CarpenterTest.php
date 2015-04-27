<?php namespace Michaeljennings\Carpenter\Tests;

use PHPUnit_Framework_TestCase;
use Michaeljennings\Carpenter\Carpenter;

class CarpenterTest extends PHPUnit_Framework_TestCase {

    public function testAddMethodAcceptsClosure()
    {
        $carpenter = $this->makeCarpenter();

        $this->assertNull($carpenter->add('test', function($table) {
            $table->setTitle('test');
        }));
    }

    public function testAddMethodAcceptsString()
    {
        $carpenter = $this->makeCarpenter();

        $this->assertNull($carpenter->add('test', 'TestClass'));
    }

    public function testGetMethodReturnsTableInstance()
    {
        $carpenter = $this->makeCarpenter();

        $carpenter->add('test', function($table) {
            $table->setTitle('test');
        });

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $carpenter->get('test'));
    }

    public function testMakeMethodReturnsTableInstance()
    {
        $carpenter = $this->makeCarpenter();

        $table = $carpenter->make('test', function($table)
        {
            $table->setTitle('test');
        });

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Table', $table);
    }

    protected function makeCarpenter()
    {
        $config = $this->getConfig();

        return new Carpenter($config);
    }

    protected function getConfig()
    {
        return require __DIR__ . '/../config/config.php';
    }

}