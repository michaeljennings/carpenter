<?php

namespace Michaeljennings\Carpenter\Tests\Store {

    use Illuminate\Database\Eloquent\Model;
    use Mockery as m;

    class ExampleEloquentModel extends Model
    {
        public function get(array $columns = ['*'])
        {
            return m::mock('Illuminate\Support\Collection', [
                'all' => []
            ]);
        }

        public function paginate($amount, array $columns = ['*'])
        {
            return m::mock('Illuminate\Support\Collection', [
                'total' => 0,
                'getTotal' => 0,
                'all' => []
            ]);
        }

        public function getQuery()
        {
            $object = new \stdClass();

            $object->orders = [];

            return $object;
        }

        public function setQuery($query)
        {
            return $this;
        }

        public function orderBy($key, $direction)
        {
            return $this;
        }

        public function foo()
        {
            return $this;
        }
    }
}

namespace Illuminate\Database\Eloquent {
    class Model {}
}