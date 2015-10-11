<?php

namespace Michaeljennings\Carpenter\Tests\Store;

use Michaeljennings\Carpenter\Store\ArrayStore;
use Michaeljennings\Carpenter\Tests\TestCase;

class ArrayStoreTest extends TestCase
{
    public function testDataCanBeSet()
    {
        $store = $this->makeArrayStore();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Store', $store->data($this->getData()));
    }

    public function testDataCanBePaginated()
    {
        $store = $this->makeArrayStore();
        $store->data($this->getData());

        $results = $store->paginate(count($this->getData()), 1, 2);

        $this->assertInternalType('array', $results);
        $this->assertCount(2, $results);
    }

    public function testCountReturnsTheCorrectTotal()
    {
        $store = $this->makeArrayStore();
        $data = $this->getData();

        $store->data($data);
        $this->assertEquals(count($data), $store->count());
    }

    public function testResultsReturnsAllData()
    {
        $store = $this->makeArrayStore();
        $data = $this->getData();

        $store->data($data);

        $this->assertInternalType('array', $store->results());
        $this->assertCount(count($data), $store->results());
    }

    public function testOrderBySortsResults()
    {
        $store = $this->makeArrayStore();
        $data = $this->getData();

        $store->data($data)->orderBy('foo');

        $results = $store->results();

        $this->assertCount(count($data), $results);
        $this->assertEquals('Test 1', $results[0]['foo']);

        $store->orderBy('foo', 'desc');

        $results = $store->results();

        $this->assertCount(count($data), $results);
        $this->assertEquals('Test 7', $results[0]['foo']);
    }

    public function testOrderBySortsPaginatedResults()
    {
        $store = $this->makeArrayStore();
        $data = $this->getData();

        $store->data($data)->orderBy('foo');

        $results = $store->paginate(count($data), 1, 2);

        $this->assertCount(2, $results);
        $this->assertEquals('Test 1', $results[0]['foo']);

        $store->orderBy('foo', 'desc');

        $results = $store->paginate(count($data), 1, 2);

        $this->assertCount(2, $results);
        $this->assertEquals('Test 7', $results[0]['foo']);
    }

    public function testRefreshOrderByReturnsInstance()
    {
        $store = $this->makeArrayStore();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Store', $store->refreshOrderBy());
    }

    public function testTheStoreWrapperCanBeRetrieved()
    {
        $store = $this->makeArrayStore();

        $this->assertEquals('Michaeljennings\Carpenter\Wrappers\ArrayWrapper', $store->getWrapper());
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\ModelNotAvailableException
     */
    public function testExceptionThrownIfAModelIsSet()
    {
        $store = $this->makeArrayStore();

        $store->model('FooModel');
    }

    protected function makeArrayStore()
    {
        return new ArrayStore();
    }
}