<?php

namespace Michaeljennings\Carpenter\Tests;

use Michaeljennings\Carpenter\Carpenter;
use PHPUnit_Framework_TestCase;

class TestCase extends PHPUnit_Framework_TestCase
{
	public function __construct()
    {
        // Fixes error with session_start() and phpunit
        ob_start();
    }

    public function setUp()
    {
        // Fixes issues with session_start and phpunit
        @session_start();
    }

	protected function makeCarpenter()
    {
        $config = $this->getConfig();

        return new Carpenter($config);
    }

    protected function makeTable()
    {
        $carpenter = $this->makeCarpenter();

        return $carpenter->make('test', function ($table) {});
    }

    protected function getData()
    {
        return [
            [
                'test' => 'Test',
                'foo' => 'bar',
                'baz' => 'qux',
            ],
            [
                'test' => 'Test',
                'foo' => 'bar',
                'baz' => 'qux',
            ],
            [
                'test' => 'Test',
                'foo' => 'bar',
                'baz' => 'qux',
            ],
        ];
    }

    protected function getConfig()
    {
        return require __DIR__ . '/../config/config.php';
    }
}