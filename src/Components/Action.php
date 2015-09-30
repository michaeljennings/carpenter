<?php

namespace Michaeljennings\Carpenter\Components;

use Closure;
use Michaeljennings\Carpenter\Nexus\MockArray;
use Michaeljennings\Carpenter\Contracts\Action as ActionContract;

class Action extends MockArray implements ActionContract
{
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

    /**
     * Set the HTML tag to wrap the action in.
     *
     * @var string
     */
    protected $tag = 'button';

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Add a callback to be run to validate that this action is to be used
     * for the current row.
     *
     * @param callable $callback
     * @return $this
     */
    public function when(Closure $callback)
    {
        if ( ! $this->whens) {
            $this->whens = [];
        }

        $this->whens[] = $callback;

        return $this;
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

        return $this->renderAction($this->tag, $this->getAttributes());
    }

    /**
     * Render the action element.
     *
     * @param  string $tag
     * @param  array  $attributes [description]
     * @return string
     */
    protected function renderAction($tag, array $attributes)
    {
        $attributes = $this->renderAttributes($attributes);

        return sprintf('<%s %s>%s</%s>', $tag, $attributes, $this->label, $tag);
    }

    /**
     * Render the element attributes to a string.
     *
     * @param  array $attributes
     * @return string
     */
    protected function renderAttributes(array $attributes)
    {
        $renderedAttributes = [];

        foreach ($attributes as $attribute => $value) {
            if ($value instanceof Closure) {
                $renderedAttributes[] = $attribute . '="' . $value($this->value, $this->row) . '"';
            } else {
                $renderedAttributes[] = $attribute . '="' . $value . '"';
            }
        }

        return implode(' ', $renderedAttributes);
    }

    /**
     * Set the column used by the action
     *
     * @param  string|boolean $column
     * @return $this
     */
    public function setColumn($column = false)
    {
        if ( ! $column) {
            return $this->column;
        }

        $this->column = $column;

        return $this;
    }

    /**
     * Alias for the setColumn method.
     *
     * @param string|bool $column
     * @return Action
     */
    public function column($column = false)
    {
        return $this->setColumn($column);
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
     * Set the row used by the action.
     *
     * @param $row
     * @return $this
     */
    public function setRow($row = false)
    {
        if ( ! $row) {
            return false;
        }

        $this->row = $row;

        return $this;
    }

    /**
     * Alias for the setRow method.
     *
     * @param string|bool $row
     * @return Action
     */
    public function row($row = false)
    {
        return $this->setRow($row);
    }

    /**
     * Set the presenter callback for the action.
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
     * @return Action
     */
    public function presenter(Closure $callback)
    {
        return $this->setPresenter($callback);
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
     * Set the label for an action
     *
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        if ($this->row && $label instanceof Closure) {
            $this->label = $label($this->value, $this->row);
        } else {
            $this->label = $label;
        }

        return $this;
    }

    /**
     * Set the HTML tag to wrap the action with.
     *
     * @param string $tag
     * @return $this
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Set the href for the anchor and set the action tag to an anchor.
     *
     * @param  string|Closure $href
     * @return $this
     */
    public function setHref($href)
    {
        $this->href = $href;
        $this->setTag('a');

        return $this;
    }

    /**
     * Alias for setHref method.
     *
     * @param  string|Closure $href
     * @return $this
     */
    public function href($href)
    {
        return $this->setHref($href);
    }

    /**
     * Set the class of the action
     *
     * @param string|Closure $class
     * @return $this
     */
    public function setClass($class)
    {
        $this->attributes['class'] = $class;

        return $this;
    }

    /**
     * Set the provided attribute for the action.
     *
     * @param string         $attribute
     * @param string|Closure $value
     * @return $this
     */
    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

}