<?php

namespace Michaeljennings\Carpenter\Tests\Store;

use Michaeljennings\Carpenter\Store\Illuminate;
use Michaeljennings\Carpenter\Tests\TestCase;
use Mockery as m;

class IlluminateStoreTest extends TestCase
{
    public function testStoreImplementsContract()
    {
        $store = $this->makeStore();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Store', $store);
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\ModelNotAvailableException
     */
    public function testModelCannotBeSet()
    {
        $store = $this->makeStore();
        $store->model('FooModel');
    }

    public function testColumnsCanBeSet()
    {
        $store = $this->makeStore();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Store', $store->select(['foo', 'bar']));
    }

    public function testResultsReturnsAnArray()
    {
        $store = $this->makeStore();
        $store->table('products');

        $this->assertInternalType('array', $store->results());
    }

    public function testCountReturnsTotalResults()
    {
        $store = $this->makeStore();
        $store->table('products');

        $this->assertEquals(0, $store->count());
    }

    public function testPaginateReturnsAnArray()
    {
        $store = $this->makeStore();
        $store->table('products');

        $this->assertInternalType('array', $store->paginate(10, 1, 5));
    }

    public function testRefreshOrderByReturnsInstance()
    {
        $store = $this->makeStore();
        $store->table('products');

        $this->assertInstanceOf('Michaeljennings\Carpenter\Store\Illuminate', $store->refreshOrderBy());
    }

    public function testOrderByReturnsInstance()
    {
        $store = $this->makeStore();
        $store->table('products');

        $this->assertInstanceOf('Michaeljennings\Carpenter\Store\Illuminate', $store->orderBy('foo', 'desc'));
    }

    public function testModelMethodsCanBeRunOnTheQuery()
    {
        $store = $this->makeStore();
        $store->table('products');

        $this->assertInstanceOf('Michaeljennings\Carpenter\Store\Illuminate', $store->foo());
    }

    public function testTheStoreWrapperCanBeRetrieved()
    {
        $store = $this->makeStore();

        $this->assertEquals('Michaeljennings\Carpenter\Wrappers\ObjectWrapper', $store->getWrapper());
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\TableNotSetException
     */
    public function testExceptionIsThrownIfModelNotSetBeforeRunningQueries()
    {
        $store = $this->makeStore();

        $store->where('foo', 'bar');
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\TableNotSetException
     */
    public function testExceptionIsThrownIfResultsIsCalledBeforeAModelIsSet()
    {
        $store = $this->makeStore();

        $store->results();
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\TableNotSetException
     */
    public function testExceptionIsThrownIfCountIsCalledBeforeAModelIsSet()
    {
        $store = $this->makeStore();

        $store->count();
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\TableNotSetException
     */
    public function testExceptionIsThrownIfPaginateIsCalledBeforeAModelIsSet()
    {
        $store = $this->makeStore();

        $store->paginate(15, 1, 1);
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\TableNotSetException
     */
    public function testExceptionIsThrownIfOrderByIsCalledBeforeAModelIsSet()
    {
        $store = $this->makeStore();

        $store->orderBy('foo');
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\TableNotSetException
     */
    public function testExceptionIsThrownIfRefreshOrderByIsCalledBeforeAModelIsSet()
    {
        $store = $this->makeStore();

        $store->refreshOrderBy();
    }

    protected function makeStore()
    {
        $db = m::mock('Illuminate\Database\DatabaseManager', [
            'table' => m::mock('Illuminate\Database\Query\Builder', [
                'get' => [],
                'count' => 0,
                'orderBy' => m::mock('Illuminate\Database\Query\Builder'),
                'paginate' => m::mock('Illuminate\Database\Query\Builder', [
                    'all' => [],
                    'total' => 0
                ]),
                'foo' => m::mock('Illuminate\Database\Query\Builder'),
            ]),
        ]);

        return new Illuminate($db);
    }
}