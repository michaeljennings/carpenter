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

    protected function makeTableWithData()
    {
        $table = $this->makeTable();
        $table->data($this->getData());
        $table->column('foo');

        return $table;
    }

    protected function getData()
    {
        return [
            [
                'id' => 1,
                'test' => 'Test',
                'foo' => 'Test 1',
                'baz' => 'Test 2',
                'nested' => [
                    'foo' => 'bar'
                ]
            ],
            [
                'id' => 2,
                'test' => 'Test 3',
                'foo' => 'Test 4',
                'baz' => 'Test 5',
                'nested' => [
                    'foo' => 'bar'
                ]
            ],
            [
                'id' => 3,
                'test' => 'Test 6',
                'foo' => 'Test 7',
                'baz' => 'Test 8',
                'nested' => [
                    'foo' => 'bar'
                ]
            ],
        ];
    }

    protected function getDataAsObjects()
    {
        $row1 = new \stdClass();

        $row1->id = 1;
        $row1->test = 'Test';
        $row1->foo = 'Test 1';
        $row1->baz = 'Test 2';
        $row1->nested = new \stdClass();
        $row1->nested->foo = 'bar';

        $row2 = new \stdClass();

        $row2->id = 2;
        $row2->test = 'Test 3';
        $row2->foo = 'Test 4';
        $row2->baz = 'Test 5';
        $row2->nested = new \stdClass();
        $row2->nested->foo = 'bar';

        $row3 = new \stdClass();

        $row3->id = 3;
        $row3->test = 'Test 6';
        $row3->foo = 'Test 7';
        $row3->baz = 'Test 8';
        $row3->nested = new \stdClass();
        $row3->nested->foo = 'bar';

        return [$row1, $row2, $row3];
    }

    protected function setPage($page = 1)
    {
        $_SERVER['QUERY_STRING'] = 'page=' . $page;
        $_SERVER['REQUEST_URI'] = 'http://localhost?page=' . $page;
        $_GET['page'] = $page;
    }

    protected function getConfig()
    {
        return require __DIR__ . '/../config/config.php';
    }
}