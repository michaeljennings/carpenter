<?php

namespace Michaeljennings\Carpenter\Tests\Wrappers;

use Michaeljennings\Carpenter\Tests\TestCase;
use Michaeljennings\Carpenter\Wrappers\Eloquent;
use Mockery as m;

class EloquentWrapperTest extends TestCase
{
    public function testValueCanBeRetrieved()
    {
        $wrapper = $this->makeWrapper();

        $this->assertTrue(! empty($wrapper->foo));
        $this->assertEquals('bar', $wrapper->foo);
    }

    public function makeWrapper()
    {
        $model = m::mock('Illuminate\Database\Eloquent\Model', []);

        $model->foo = 'bar';

        return new Eloquent($model);
    }
}