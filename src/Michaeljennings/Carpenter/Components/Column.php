<?php namespace Michaeljennings\Carpenter\Components;

use Closure;

class Column extends ArrayableComponent {

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

    public function __construct($column = false, $key, $driverContainer)
    {
        if ($column) {
            $this->createHref($column, $key, $driverContainer);
        }
    }

    /**
     * Create the href for the column
     *
     * @param  string          $column
     * @param  string          $key
     * @param  DriverContainer $driverContainer
     * @return void
     */
    private function createHref($column, $key, $driverContainer)
    {
        if ($this->sortable) {
            if ($driverContainer->session->get('michaeljennings.carpenter.' . $key . '.sort') == $column) {
                if ($driverContainer->session->has('michaeljennings.carpenter.' . $key . '.dir')) {
                    $splitUrl = explode('?', $_SERVER['REQUEST_URI']);
                    if (count($splitUrl) < 2) {
                        $driverContainer->session->forget('michaeljennings.carpenter.' . $key . '.sort');
                        $driverContainer->session->forget('michaeljennings.carpenter.' . $key . '.dir');
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
     */
    public function presenter(Closure $callback)
    {
        $this->presenter = $callback;
    }

    /**
     * Check if there is a presenter callback for the column
     * @return boolean
     */
    public function hasPresenter()
    {
        return is_null($this->presenter) ? false : true;
    }

    /**
     * Accessor for the presenter
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