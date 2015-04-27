<?php namespace Michaeljennings\Carpenter\Tests;

use PHPUnit_Framework_TestCase;
use Michaeljennings\Carpenter\Carpenter;

class TableTest extends PHPUnit_Framework_TestCase {

    public function __construct()
    {
        // Fixes error with session_start() and phpunit
        ob_start();
    }

    public function testCanAddAndGetTitle()
    {
        $carpenter = $this->makeCarpenter();

        $carpenter->add('test', function($table) {
            $table->setTitle('test');
        });

        $this->assertEquals('test', $carpenter->get('test')->getTitle());
    }

    public function testCanAddAndGetActions()
    {
        $carpenter = $this->makeCarpenter();

        $carpenter->add('test', function($table) {
            $table->action('create');
        });

        $this->assertEquals(1, count($carpenter->get('test')->actions()));
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