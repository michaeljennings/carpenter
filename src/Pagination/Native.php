<?php

namespace Michaeljennings\Carpenter\Pagination;

use Michaeljennings\Carpenter\Contracts\Paginator;

class Native implements Paginator
{
    /**
     * The current page.
     *
     * @var int
     */
    protected $page = 1;

    /**
     * The amount of results per page.
     *
     * @var int|string
     */
    protected $perPage;

    /**
     * The total results.
     *
     * @var int|string
     */
    protected $total;

    /**
     * The total amount of pages.
     *
     * @var int|string
     */
    protected $totalPages;

    /**
     * Create a new paginator.
     *
     * @param  string|integer $total
     * @param  string|integer $perPage
     * @return $this
     */
    public function make($total, $perPage)
    {
        $this->total = $total;
        $this->perPage = $perPage;
        $this->totalPages = $this->calculatePages($this->total, $this->perPage);
        $this->page = $this->getCurrentPage();

        return $this;
    }

    /**
     * Get the pagination links.
     *
     * @return string
     */
    public function links()
    {
        if ($this->totalPages > 1) {
            return sprintf('<ul class="pagination">%s %s %s</ul>', $this->getPrevious(), $this->getLinks(), $this->getNext());
        }

        return null;
    }

    /**
     * Get the current page.
     *
     * @return integer|string
     */
    public function currentPage()
    {
        return $this->page;
    }

    /**
     * Calculate the total amount of pages.
     *
     * @param $total
     * @param $perPage
     * @return float
     */
    protected function calculatePages($total, $perPage)
    {
        return ceil($total / $perPage);
    }

    /**
     * Get the current page the user is viewing.
     *
     * @return int
     */
    protected function getCurrentPage()
    {
        if (isset($_GET['page'])) {
            $this->page = (int)$_GET['page'];
        }

        return $this->page;
    }

    /**
     * Create the previous page link.
     *
     * @return null|string
     */
    protected function getPrevious()
    {
        if ($this->page > 1) {
            return $this->createLink($this->page - 1, 'Prev');
        }

        return null;
    }

    /**
     * Get all of the links for the pages.
     *
     * @return string
     */
    protected function getLinks()
    {
        $links = [];

        foreach (range(1, $this->totalPages) as $page) {
            $links[] = $this->createLink($page);
        }

        return implode('', $links);
    }

    /**
     * Get the next page link.
     *
     * @return null|string
     */
    protected function getNext()
    {
        if ($this->page < $this->totalPages) {
            return $this->createLink($this->page + 1, 'Next');
        }

        return null;
    }

    /**
     * Create a pagination link.
     *
     * @param      $page
     * @param null $label
     * @return string
     */
    protected function createLink($page, $label = null)
    {
        $label = $label ?: $page;
        $active = $page == $this->page ? 'class="active"' : '';

        return "<li {$active}><a href=\"{$this->getPath($page)}\">{$label}</a></li>";
    }

    /**
     * Get the path for a pagination link.
     *
     * @param $page
     * @return string
     */
    protected function getPath($page)
    {
        $path = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);

        return $path . '?page=' . $page;
    }
}