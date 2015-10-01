<?php

namespace Michaeljennings\Carpenter\Tests\Pagination;

use Michaeljennings\Carpenter\Pagination\Native;
use Michaeljennings\Carpenter\Tests\TestCase;

class NativePaginatorTest extends TestCase
{
    public function testPaginatorImplementsContract()
    {
        $paginator = $this->makeNativePaginator();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Paginator', $paginator);
    }

    public function testMakeReturnsPaginator()
    {
        $paginator = $this->makeNativePaginator();

        $this->assertInstanceOf('Michaeljennings\Carpenter\Contracts\Paginator', $paginator->make(1, 10));
    }

    public function testLinksReturnsNullIfThereAreNoLinks()
    {
        $paginator = $this->makeNativePaginator();
        $paginator->make(1, 10);

        $this->assertNull($paginator->links());
    }

    public function testLinksAreRenderedToAList()
    {
        $paginator = $this->makeNativePaginator();
        $paginator->make(30, 10);

        $links = $paginator->links();

        $this->assertContains('ul', $links);
        $this->assertContains('li', $links);
        $this->assertContains('Next', $links);
        $this->assertContains('1', $links);
    }

    public function testPrevIsShownIfNotOnFirstPage()
    {
        $this->setPage(2);

        $paginator = $this->makeNativePaginator();
        $paginator->make(30, 10);

        $links = $paginator->links();
        $this->assertContains('Prev', $links);
    }

    public function testCurrentPageReturnsTheCorrectPage()
    {
        $paginator = $this->makeNativePaginator();

        $this->assertEquals(1, $paginator->currentPage());
    }

    protected function makeNativePaginator()
    {
        return new Native();
    }
}