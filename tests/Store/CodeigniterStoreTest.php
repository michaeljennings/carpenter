<?php

namespace Michaeljennings\Carpenter\Tests\Store {

    use Michaeljennings\Carpenter\Store\Codeigniter;
    use Michaeljennings\Carpenter\Tests\TestCase;

    class CodeigniterStoreTest extends TestCase
    {
        public function testStoreImplementsContract()
        {
            $store = $this->makeStore();
            $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Store', $store);
        }

        public function testThatModelCanBeSet()
        {
            $store = $this->makeStore();
            $this->assertInstanceOf('Michaeljennings\Carpenter\Store\Codeigniter', $store->model('FooModel'));
        }

        public function testResultsReturnsResultsWithoutAQuery()
        {
            $store = $this->makeStore();

            $this->assertInternalType('array', $store->results());
        }

        public function testResultsCanBeReturnedWhenAQueryHasBeenRun()
        {
            $store = $this->makeStore();

            $this->assertInstanceOf('Michaeljennings\Carpenter\Store\Codeigniter', $store->order_by('foo'));
            $this->assertInstanceOf('Michaeljennings\Carpenter\Store\Codeigniter', $store->orderBy('baz'));
            $this->assertInternalType('array', $store->results());
        }

        public function testCountReturnsTotalResults()
        {
            $store = $this->makeStore();

            $this->assertEquals(0, $store->count());
            $store->order_by('foo');
            $this->assertEquals(0, $store->count());
        }

        public function testResultsCanBePaginatedWithoutQuery()
        {
            $store = $this->makeStore();

            $this->assertInternalType('array', $store->paginate(10, 1, 5));
        }

        public function testResultsCanBePaginatedWhenAQueryHasBeenRun()
        {
            $store = $this->makeStore();

            $this->assertInstanceOf('Michaeljennings\Carpenter\Store\Codeigniter', $store->order_by('foo'));
            $this->assertInternalType('array', $store->paginate(10, 1, 5));
        }

        public function testRefreshOrderByReturnsInstance()
        {
            $store = $this->makeStore();

            $this->assertInstanceOf('Michaeljennings\Carpenter\Store\Codeigniter', $store->refreshOrderBy());
        }

        public function testModelMethodsCanBeRunOnQuery()
        {
            $store = $this->makeStore();

            $this->assertInstanceOf('Michaeljennings\Carpenter\Store\Codeigniter', $store->model('FooModel'));
            $this->assertEquals('bar', $store->foo());
        }

        public function testTheStoreWrapperCanBeRetrieved()
        {
            $store = $this->makeStore();

            $this->assertEquals('Michaeljennings\Carpenter\Wrappers\ArrayWrapper', $store->getWrapper());
        }

        public function makeStore()
        {
            return new Codeigniter();
        }
    }
}

namespace Michaeljennings\Carpenter\Store {

    use Mockery as m;

    function get_instance()
    {
        $object = new \stdClass();

        $mockLoader = m::mock('Loader', [
            'model' => 'Model',
        ]);

        $object->load = $mockLoader;

        $mockDb = m::mock('DB', [
            'get' => m::mock('Query', [
                'result' => [],
            ]),
            'order_by' => m::mock('Query', [
                'get' => m::mock('Query', [
                    'result' => [],
                ]),
                'order_by' => m::mock('Query', [
                    'get' => m::mock('Query', [
                        'result' => [],
                    ]),
                ]),
                'count_all_results' => 0,
                'limit' => m::mock('Query', [
                    'get' => m::mock('Query', [
                        'result' => []
                    ]),
                ]),
            ]),
            'limit' => m::mock('DB', [
                'get' => m::mock('Query', [
                    'result' => [],
                ]),
            ])
        ]);

        $object->db = $mockDb;

        $mockQuery = m::mock('Query', [
            'get' => m::mock('Query', [
                'result' => []
            ]),
            'count_all_results' => 0
        ]);

        $object->query = $mockQuery;

        $mockModel = new FooModel();

        $object->FooModel = $mockModel;

        return $object;
    }

    class FooModel
    {
        public function foo()
        {
            return 'bar';
        }
    }

}