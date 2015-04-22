<?php namespace Michaeljennings\Carpenter\Components;

use Closure;
use Michaeljennings\Carpenter\Nexus\MockArray;
use Michaeljennings\Carpenter\Session\SessionManager;
use Michaeljennings\Carpenter\Contracts\Column as ColumnContract;

class Column extends MockArray implements ColumnContract {

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

    public function __construct($column = false, $key, SessionManager $session, array $config)
    {
        $this->session = $session;
        $this->config = $config;

        if ($column) {
            $this->createHref($column, $key);
        }
    }

    /**
     * Create the href for the column if the column is sortable.
     *
     * @param  string $column
     * @param  string $key
     * @return void
     */
    private function createHref($column, $key)
    {
        if ($this->sortable) {

            if ($this->session->get($this->config['session']['key'] . '.' . $key . '.sort') == $column) {
                if ($this->session->has($this->config['session']['key'] . '.' . $key . '.dir')) {
                    $splitUrl = explode('?', $_SERVER['REQUEST_URI']);
                    if (count($splitUrl) < 2) {
                        $this->session->forget($this->config['session']['key'] . '.' . $key . '.sort');
                        $this->session->forget($this->config['session']['key'] . '.' . $key . '.dir');
                        $this->href = '?sort=' . $column;
                    } else {
                        $this->href = $splitUrl[0];
                        $this->sort = 'up';
                    }
                } else {
                    $this->href = '?sort=' . $column . '&dir=desc';
                    $this->sort = 'down';
                }
            } else {
                $this->href = '?sort=' . $column;
            }
        }
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
        if (is_null($this->presenter)) return false;

        return $this->presenter;
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