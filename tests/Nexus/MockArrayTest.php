<?php

namespace Michaeljennings\Carpenter\Tests\Nexus;

use Michaeljennings\Carpenter\Nexus\MockArray;
use Michaeljennings\Carpenter\Tests\TestCase;

class MockArrayTest extends TestCase
{
    public function testMockArrayImplementsArrayAccess()
    {
        $this->assertInstanceOf('ArrayAccess', $this->makeMockArray());
    }

    public function testDataCanBeAccessedAsArrayOrObject()
    {
        $test = $this->makeMockArray();

        $this->assertEquals('bar', $test['foo']);
        $this->assertEquals('bar', $test->foo);
    }

    public function testAllAttributesCanBeRetrieved()
    {
        $test = $this->makeMockArray();

        $this->assertCount(2, $test->getAttributes());
    }

    public function testMockArrayActsLikeArray()
    {
        $test = $this->makeMockArray();

        $this->assertTrue(isset($test['foo']));
        $this->assertFalse(isset($test['test']));

        $test['test'] = 'hello world';

        $this->assertEquals('hello world', $test['test']);

        $this->assertTrue(isset($test['test']));

        unset($test['test']);

        $this->assertFalse(isset($test['test']));
    }

    public function testItemsCanBeAddedDynamically()
    {
        $array = $this->makeMockArray();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Nexus\MockArray', $array->test('hello world'));
        $this->assertEquals('hello world', $array->get('test'));
    }

    public function testItemsCanBeAddedDynamicallyWithoutSpecifyingAValue()
    {
        $array = $this->makeMockArray();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Nexus\MockArray', $array->fooBar());
        $this->assertEquals('fooBar', $array->get('fooBar'));
    }

    public function testDefaultReturnedIfValueNotSet()
    {
        $array = $this->makeMockArray();

        $this->assertEquals('test', $array->get('key', 'test'));
    }

    protected function makeMockArray()
    {
        return new MockArray(['foo' => 'bar', 'baz' => 'qux']);
    }
}