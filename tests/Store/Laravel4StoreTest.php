<?php

namespace Michaeljennings\Carpenter\Tests\Store;

use Michaeljennings\Carpenter\Store\Laravel4\Eloquent;
use Michaeljennings\Carpenter\Tests\TestCase;
use Mockery as m;

class Laravel4StoreTest extends TestCase
{
    public function testCountReturnsTotalResults()
    {
        $store = $this->makeStore();
        $model = new ExampleEloquentModel();

        $store->model($model);
        $this->assertEquals(0, $store->count());
    }

    /**
     * @expectedException \Michaeljennings\Carpenter\Exceptions\ModelNotSetException
     */
    public function testExceptionIsThrownIfCountIsCalledBeforeModelIsSet()
    {
        $store = $this->makeStore();

        $store->count();
    }

    public function makeStore()
    {
        $app = [
            'paginator' => m::mock('Paginator', [
                'make' => m::mock('Paginator', [
                    'links' => '',
                    'getCurrentPage' => 1,
                    'setPageName' => true,
                ]),
                'setPageName' => null,
            ]),
        ];

        return new Eloquent($app);
    }
}