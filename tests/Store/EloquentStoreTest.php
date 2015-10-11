<?php

namespace Michaeljennings\Carpenter\Tests\Store;

use Michaeljennings\Carpenter\Store\Eloquent;
use Michaeljennings\Carpenter\Tests\Store\ExampleEloquentModel;
use Michaeljennings\Carpenter\Tests\TestCase;

class EloquentStoreTest extends TestCase
{
    public function testEloquentStoreImplementsContract()
    {
        $store = $this->makeStore();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Store', $store);
    }

    public function testModelCanBeSet()
    {
        $store = $this->makeStore();
        $model = new ExampleEloquentModel();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Store', $store->model($model));
    }

    public function testColumnsCanBeSet()
    {
        $store = $this->makeStore();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Store', $store->select(['foo', 'bar']));
    }

    public function testResultsReturnsAnArray()
    {
        $store = $this->makeStore();
        $model = new ExampleEloquentModel();

        $store->model($model);
        $this->assertInternalType('array', $store->results());
    }

    public function testCountReturnsTotalResults()
    {
        $store = $this->makeStore();
        $model = new ExampleEloquentModel();

        $store->model($model);
        $this->assertEquals(0, $store->count());
    }

    public function testPaginateReturnsAnArray()
    {
        $store = $this->makeStore();
        $model = new ExampleEloquentModel();

        $store->model($model);
        $this->assertInternalType('array', $store->paginate(10, 1, 5));
    }

    public function testRefreshOrderByReturnsInstance()
    {
        $store = $this->makeStore();
        $model = new ExampleEloquentModel();

        $store->model($model);
        $this->assertInstanceOf('Michaeljennings\Carpenter\Store\Eloquent', $store->refreshOrderBy());
    }

    public function testOrderByReturnsInstance()
    {
        $store = $this->makeStore();
        $model = new ExampleEloquentModel();

        $store->model($model);
        $this->assertInstanceOf('Michaeljennings\Carpenter\Store\Eloquent', $store->orderBy('foo', 'desc'));
    }

    public function testModelMethodsCanBeRunOnTheQuery()
    {
        $store = $this->makeStore();
        $model = new ExampleEloquentModel();

        $store->model($model);
        $this->assertInstanceOf('Michaeljennings\Carpenter\Store\Eloquent', $store->foo());
    }

    public function testTheStoreWrapperCanBeRetrieved()
    {
        $store = $this->makeStore();

        $this->assertEquals('Michaeljennings\Carpenter\Wrappers\ObjectWrapper', $store->getWrapper());
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\ModelNotSetException
     */
    public function testExceptionIsThrownIfModelNotSetBeforeRunningQueries()
    {
        $store = $this->makeStore();

        $store->where('foo', 'bar');
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\ModelNotSetException
     */
    public function testExceptionIsThrownIfResultsIsCalledBeforeAModelIsSet()
    {
        $store = $this->makeStore();

        $store->results();
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\ModelNotSetException
     */
    public function testExceptionIsThrownIfCountIsCalledBeforeAModelIsSet()
    {
        $store = $this->makeStore();

        $store->count();
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\ModelNotSetException
     */
    public function testExceptionIsThrownIfPaginateIsCalledBeforeAModelIsSet()
    {
        $store = $this->makeStore();

        $store->paginate(15, 1, 1);
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\ModelNotSetException
     */
    public function testExceptionIsThrownIfOrderByIsCalledBeforeAModelIsSet()
    {
        $store = $this->makeStore();

        $store->orderBy('foo');
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\ModelNotSetException
     */
    public function testExceptionIsThrownIfRefreshOrderByIsCalledBeforeAModelIsSet()
    {
        $store = $this->makeStore();

        $store->refreshOrderBy();
    }

    public function makeStore()
    {
        return new Eloquent();
    }
}