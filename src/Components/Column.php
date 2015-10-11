<?php

namespace Michaeljennings\Carpenter\Components;

use Closure;
use Michaeljennings\Carpenter\Nexus\MockArray;
use Michaeljennings\Carpenter\Session\SessionManager;
use Michaeljennings\Carpenter\Contracts\Column as ColumnContract;
use Michaeljennings\Carpenter\Table;

class Column extends MockArray implements ColumnContract
{
    /**
     * The column key being used.
     *
     * @var string|bool
     */
    protected $column;

    /**
     * The unique table key.
     *
     * @var string
     */
    protected $key;

    /**
     * The table the column belongs to.
     *
     * @var Table
     */
    protected $table;

    /**
     * An instance of the carpenter session manager.
     *
     * @var SessionManager
     */
    protected $session;

    /**
     * The carpenter config.
     *
     * @var array
     */
    protected $config = [];

    /**
     * A callback to be run on the column cells
     *
     * @var Closure
     */
    protected $presenter;

    /**
     * The link for the header
     *
     * @var string
     */
    protected $href;

    /**
     * The label to appear at the top of the column.
     *
     * @var string
     */
    protected $label;

    /**
     * Set if the column is sortable or not.
     *
     * @var bool
     */
    protected $sortable = true;

    /**
     * A custom sort for the column.
     *
     * @var Closure
     */
    protected $customSort;

    /**
     * Set if the column is currently being sorted.
     *
     * @var bool|null
     */
    protected $active;

    /**
     * Set if the column is being sorted in descending order.
     *
     * @var bool
     */
    protected $descending;

    public function __construct($column = false, $key, SessionManager $session, array $config)
    {
        $this->column = $column;
        $this->key = $key;
        $this->session = $session;
        $this->config = $config;

        if ($column) {
            $this->href = $this->createHref($column, $key);
        }
    }

    /**
     * Create the href for the column if the column is sortable.
     *
     * @param  string $column
     * @param  string $key
     * @return void
     */
    protected function createHref($column, $key)
    {
        $query = ['sort' => $column, 'table' => urlencode($key)];

        if ($this->isActive()) {
            if ($this->isDescending()) {
                if (empty($_SERVER['QUERY_STRING'])) {
                    $this->clearSession($key);
                } else {
                    $this->sort = 'up';

                    return $this->getUrl();
                }
            } else {
                $query['dir'] = 'desc';
                $this->sort = 'down';
            }
        }

        return '?' . $this->renderQueryString($query);
    }

    /**
     * Render the query string from the query elements.
     *
     * @param  array $queries
     * @return string
     */
    protected function renderQueryString(array $queries)
    {
        $renderedQuery = [];

        foreach ($queries as $query => $value) {
            $renderedQuery[] = $query . '=' . $value;
        }

        return implode('&', $renderedQuery);
    }

    /**
     * Clear the sort keys from the session
     *
     * @param  string $key
     * @return bool
     */
    protected function clearSession($key)
    {
        $this->session->forget($this->config['session']['key'] . '.' . $key . '.sort');
        $this->session->forget($this->config['session']['key'] . '.' . $key . '.dir');

        return true;
    }

    /**
     * Set the presenter callback for the column cells
     *
     * @param  Closure $callback
     * @return $this
     */
    public function setPresenter(Closure $callback)
    {
        $this->presenter = $callback;

        return $this;
    }

    /**
     * Alias for the setPresenter method.
     *
     * @param callable $callback
     * @return Column
     */
    public function presenter(Closure $callback)
    {
        return $this->setPresenter($callback);
    }

    /**
     * Check if there is a presenter callback for the column
     *
     * @return boolean
     */
    public function hasPresenter()
    {
        return ! empty($this->presenter);
    }

    /**
     * Return the column presenter.
     *
     * @return Closure|boolean
     */
    public function getPresenter()
    {
        if (is_null($this->presenter)) {
            return false;
        }

        return $this->presenter;
    }

    /**
     * Set a custom sort for the column.
     *
     * @param Closure $sort
     * @return $this
     */
    public function sort(Closure $sort)
    {
        $this->customSort = $sort;

        return $this;
    }

    /**
     * Check if the column has a custom sort.
     *
     * @return bool
     */
    public function hasSort()
    {
        return isset($this->customSort);
    }

    /**
     * Get the custom sort for the column.
     *
     * @return Closure
     */
    public function getSort()
    {
        return $this->customSort;
    }

    /**
     * Accessor for the columns href
     *
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Set the label for the column.
     *
     * @param $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the label for the column.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set this column as sortable.
     *
     * @return $this
     */
    public function sortable()
    {
        $this->sortable = true;

        return $this;
    }

    /**
     * Set this column as unsortable.
     *
     * @return $this
     */
    public function unsortable()
    {
        $this->sortable = false;

        return $this;
    }

    /**
     * Return whether the column is sortable or not.
     *
     * @return bool
     */
    public function isSortable()
    {
        return $this->sortable;
    }

    /**
     * Check if the column is being sorted.
     *
     * @return bool
     */
    public function isActive()
    {
        if ( ! isset($this->active)) {
            $this->active = $this->session->get($this->config['session']['key'] . '.' . $this->key . '.sort') == $this->column;
        }

        return $this->active;
    }

    /**
     * Check if the column is being sorted in descending order.
     *
     * @return bool
     */
    public function isDescending()
    {
        if ( ! isset($this->descending)) {
            $this->descending =  $this->session->has($this->config['session']['key'] . '.' . $this->key . '.dir');
        }

        return $this->descending;
    }

    /**
     * Get the current url without a query string.
     */
    protected function getUrl()
    {
        return str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
    }

    /**
     * Set an undefined item into the attributes array
     *
     * @param  string $name      The attribute name
     * @param  array  $arguments The attribute arguments
     * @return object            Self
     */
    public function __call($name, $arguments)
    {
        $this->attributes[$name] = $arguments[0];

        return $this;
    }
}