<?php

namespace Michaeljennings\Carpenter\Tests\Store;

use Michaeljennings\Carpenter\Store\Laravel4\EloquentStore;
use Michaeljennings\Carpenter\Tests\TestCase;

class Laravel4StoreTest extends TestCase
{
    public function testCountReturnsTotalResults()
    {
        $store = $this->makeStore();

        $store->model('Michaeljennings\Carpenter\Tests\Store\ExampleEloquentModel');
        $this->assertEquals(0, $store->count());
    }

    public function makeStore()
    {
        return new EloquentStore();
    }
}