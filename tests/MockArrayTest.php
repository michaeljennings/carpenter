<?php

namespace Michaeljennings\Carpenter\Tests;

use Michaeljennings\Carpenter\Nexus\MockArray;

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

    protected function makeMockArray()
    {
        return new MockArray(['foo' => 'bar', 'baz' => 'qux']);
    }
}