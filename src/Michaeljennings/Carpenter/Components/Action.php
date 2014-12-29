<?php namespace Michaeljennings\Carpenter\Components;

use Closure;
use Illuminate\Support\Fluent;

class Action extends Fluent {

    /**
     * The column used by the action
     *
     * @var string
     */
    protected $column = 'id';

    /**
     * The label for the action
     *
     * @var string
     */
    protected $label;

    /**
     * The presenter
     *
     * @var Closure
     */
    protected $presenter = false;

    /**
     * The when callback arraey
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
     * @param  string $content
     * @return object
     */
    public function column($column = false)
    {
        if (!$column) return $this->column;

        $this->column = $column;
        return $this;
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

    public function valid($model)
    {
        if ($this->whens) {
            foreach ($this->whens as $when) {
                if ( ! $when($model)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function when(Closure $callback)
    {
        if ( ! $this->whens) {
            $this->whens = [];
        }

        $this->whens[] = $callback;
    }

    /**
     * Set the presenter callback for the action
     *
     * @param  Closure $callback
     * @return object
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
                        $action .= 'href="'.$val($this->value).'"';
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
     * Set the class of the action
     *
     * @param string $class
     * @return object
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
     * @return object
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Set an undefined item in to the attributes array
     *
     * @param  string $name      The attribute name
     * @param  array  $arguments The attribute arguments
     * @return object            Self
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