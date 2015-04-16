<?php namespace Michaeljennings\Carpenter\Components;

use Closure;
use Illuminate\Support\Fluent;
use Michaeljennings\Carpenter\Contracts\Action as ActionContract;

class Action extends Fluent implements ActionContract {

    /**
     * The column used by the action.
     *
     * @var string
     */
    protected $column = 'id';

    /**
     * If this actions is of a row context, then this is the current row being
     * looped through.
     *
     * @var mixed
     */
    protected $row = false;

    /**
     * The label for the action.
     *
     * @var string
     */
    protected $label;

    /**
     * The column presenter
     *
     * @var Closure|boolean
     */
    protected $presenter = false;

    /**
     * The when callback array
     *
     * @var Closure
     */
    protected $whens = false;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Set the column used by the action
     *
     * @param  string|boolean $column
     * @return $this
     */
    public function column($column = false)
    {
        if (!$column) return $this->column;

        $this->column = $column;
        return $this;
    }

    /**
     * Set the row used by the action.
     *
     * @param $row
     * @return $this
     */
    public function row($row = false)
    {
        if ( ! $row) return false;
        $this->row = $row;

        return $this;
    }

    /**
     * Add a callback to be run to validate that this action is to be used
     * for the current row.
     *
     * @param callable $callback
     */
    public function when(Closure $callback)
    {
        if ( ! $this->whens) {
            $this->whens = [];
        }

        $this->whens[] = $callback;
    }

    /**
     * Check that the current row passes all of the when callbacks.
     *
     * @param $row
     * @return bool
     */
    public function valid($row)
    {
        if ($this->whens) {
            foreach ($this->whens as $when) {
                if ( ! $when($row)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Set the presenter callback for the action
     *
     * @param  Closure $callback
     * @return $this
     */
    public function presenter(Closure $callback)
    {
        $this->presenter = $callback;

        return $this;
    }

    /**
     * Render the html for the action
     *
     * @return string
     */
    public function render()
    {
        if ($this->getPresenter()) {
            $callback = $this->getPresenter();
            $callback($this);
        }

        if (isset($this->href)) {
            $action = '<a ';
        } else {
            $action = '<button type="submit"';
        }

        foreach ($this->getAttributes() as $key => $val) {
            switch ($key) {
                case "href":
                    if ($val instanceof Closure) {
                        $action .= 'href="'.$val($this->value, $this->row).'"';
                    } else {
                        $action .= 'href="'.rtrim($val, '/').(isset($this->value) ? '/'.$this->value.'" ' : '" ');
                    }
                    break;
                default:
                    $action .= $key.'="'.$val.'" ';
                    break;
            }
        }

        if (isset($this->href)) {
            $action .= '>'.$this->label.'</a>';
        } else {
            $action .= '>'.$this->label.'</button>';
        }

        return $action;
    }

    /**
     * Return the action's column
     *
     * @return string
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * Return the presenter callback
     *
     * @return closure
     */
    public function getPresenter()
    {
        return $this->presenter;
    }

    /**
     * Set the class of the action
     *
     * @param string $class
     * @return $this
     */
    public function setClass($class)
    {
        $this->attributes['class'] = $class;

        return $this;
    }

    /**
     * Set the label for an action
     *
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set an undefined item in to the attributes array.
     *
     * @param  string $name      The attribute name
     * @param  array  $arguments The attribute arguments
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if (!empty($arguments)) {
            $this->attributes[$name] = $arguments[0];
        } else {
            $this->attributes[$name] = $name;
        }
        return $this;
    }
}