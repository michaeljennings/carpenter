<?php

namespace Michaeljennings\Carpenter\Tests;

use Michaeljennings\Carpenter\Contracts\Table as TableContract;

class ExampleTable
{
    public function build(TableContract $table)
    {
        $table->setTitle('test title');
    }
}