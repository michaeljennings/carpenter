<?php

namespace Michaeljennings\Carpenter\Tests\Store;

use Michaeljennings\Carpenter\Store\Laravel4\Eloquent;
use Michaeljennings\Carpenter\Tests\TestCase;

class Laravel4StoreTest extends TestCase
{
    public function testCountReturnsTotalResults()
    {
        $store = $this->makeStore();
        $model = new ExampleEloquentModel();

        $store->model($model);
        $this->assertEquals(0, $store->count());
    }

    public function makeStore()
    {
        return new Eloquent();
    }
}