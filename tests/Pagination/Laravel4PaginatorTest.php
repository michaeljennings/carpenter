<?php

namespace Michaeljennings\Carpenter\Tests\Pagination;

use Michaeljennings\Carpenter\Pagination\Laravel4\Illuminate;
use Michaeljennings\Carpenter\Tests\TestCase;
use Mockery as m;

class Laravel4PaginatorTest extends TestCase
{
    public function testPaginatorImplementsContract()
    {
        $paginator = $this->makePaginator();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Paginator', $paginator);
    }

    public function testMakeMethodReturnsInstance()
    {
        $paginator = $this->makePaginator();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Paginator', $paginator->make(10, 5, 'page'));
    }

    public function testLinksMethodReturnsString()
    {
        $paginator = $this->makePaginator();
        $paginator->make(10, 5, 'page');

        $this->assertInternalType('string', $paginator->links());
    }

    public function testCurrentPageMethodReturnsInt()
    {
        $paginator = $this->makePaginator();
        $paginator->make(10, 5, 'page');

        $this->assertInternalType('integer', $paginator->currentPage());
    }

    public function makePaginator()
    {
        $app = [
            'paginator' => m::mock('Paginator', [
                'make' => m::mock('Paginator', [
                    'links' => '',
                    'getCurrentPage' => 1,
                    'setPageName' => true,
                ]),
            ]),
        ];

        return new Illuminate($app);
    }
}