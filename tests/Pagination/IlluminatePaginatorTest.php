<?php

namespace Michaeljennings\Carpenter\Tests\Pagination;

use Michaeljennings\Carpenter\Pagination\IlluminateDriver;
use Michaeljennings\Carpenter\Tests\TestCase;
use Mockery as m;

class IlluminatePaginatorTest extends TestCase
{
    protected $page = 1;

    public function testPaginatorImplementsContract()
    {
        $paginator = $this->makePaginator();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Paginator', $paginator);
    }

    public function testMakeReturnsPaginator()
    {
        $paginator = $this->makePaginator();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Paginator', $paginator->make(1, 10));
    }

    public function testLinksReturnsEmptyIfThereAreNoLinks()
    {
        $paginator = $this->makePaginator();
        $paginator->make(1, 10);

        $this->assertEmpty($paginator->links());
    }

    public function testLinksAreRenderedToAList()
    {
        $paginator = $this->makePaginator();
        $paginator->make(30, 10);

        $links = $paginator->links();

        $this->assertContains('ul', $links);
        $this->assertContains('li', $links);
        $this->assertContains('1', $links);
    }

    public function testPrevIsShownIfNotOnFirstPage()
    {
        $this->setPage(2);

        $paginator = $this->makePaginator();
        $paginator->make(30, 10);

        $links = $paginator->links();
        $this->assertContains('prev', $links);
        $this->setPage(1);
    }

    public function testNextIsNotShownIfOnLastPage()
    {
        $this->setPage(3);

        $paginator = $this->makePaginator();
        $paginator->make(30, 10);

        $links = $paginator->links();
        $this->assertNotContains('next', $links);
        $this->setPage(1);
    }

    public function testCurrentPageReturnsTheCorrectPage()
    {
        $paginator = $this->makePaginator();
        $paginator->make(30, 10);

        $this->assertEquals(1, $paginator->currentPage());
    }
    protected function makePaginator()
    {
        $app = [
            'request' => m::mock('Illuminate\Http\Request', [
                'input' => $this->page,
                'url' => 'http://localhost',
            ])
        ];

        return new IlluminateDriver($app);
    }

    protected function setPage($page = 1)
    {
        $this->page = $page;
    }
}