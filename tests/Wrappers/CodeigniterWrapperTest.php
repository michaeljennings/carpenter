<?php

namespace Michaeljennings\Carpenter\Tests\Wrappers;

use Michaeljennings\Carpenter\Tests\TestCase;
use Michaeljennings\Carpenter\Wrappers\Codeigniter;

class CodeigniterWrapperTest extends TestCase
{
    public function testDataCanBeAddedAsArray()
    {
        $wrapper = new Codeigniter(['foo' => 'bar']);

        $this->assertEquals('bar', $wrapper->foo);
    }

    public function testDataCanBeAddedAsObject()
    {
        $object = new \stdClass();
        $object->foo = 'bar';

        $wrapper = new Codeigniter($object);

        $this->assertEquals('bar', $wrapper->foo);
    }
}