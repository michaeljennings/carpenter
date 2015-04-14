<?php namespace Michaeljennings\Carpenter;

use Closure;
use Michaeljennings\Carpenter\Components\Row;
use Michaeljennings\Carpenter\Components\Cell;
use Michaeljennings\Carpenter\Components\Action;
use Michaeljennings\Carpenter\Components\Column;
use Michaeljennings\Carpenter\Contracts\Table as TableContract;

class Table implements TableContract {

    /**
     * A unique key for the table, used to help keep column orders unique
     * to each table instance.
     *
     * @var string
     */
    protected $key;

    /**
     * The carpenter config.
     *
     * @var array
     */
    protected $config = [];

    /**
     * The drivers used to create a table instance.
     *
     * @var DriverContainer
     */
    protected $drivers;

    /**
     * The table columns.
     *
     * @var array
     */
    protected $columns = [];

    /**
     * The table actions.
     *
     * @var array
     */
    protected $actions = [];

    /**
     * The table rows.
     *
     * @var array
     */
    protected $rows = [];

    /**
     * The amount to paginate the results by.
     *
     * @var integer|string|bool
     */
    protected $paginate = false;

    /**
     * An array of filters to be run on the table results.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Set the template for to be used to render the table.
     *
     * @var string|bool
     */
    protected $template = false;

    /**
     * Set the table title.
     *
     * @var string|null
     */
    protected $title;

    /**
     * The pagination links.
     *
     * @var string|bool
     */
    protected $links = false;

    /**
     * In the default templates each table is wrapped in a form so that post actions
     * can be used. By default the form actions is left blank, or you can set a form
     * action if you wish the form to go else where.
     *
     * @var string|null
     */
    protected $formAction;

    /**
     * In the default templates each table is wrapped in a form so that post actions
     * can be used. By default the form posts or you can used formMethod() to set a
     * different method.
     *
     * @var string
     */
    protected $formMethod = 'POST';

    public function __construct($key, DriverContainer $drivers, array $config)
    {
        $this->key = $key;
        $this->drivers = $drivers;
        $this->config = $config;
    }

    /**
     * Add a new column to the table.
     *
     * @param string $name
     * @return \Michaeljennings\Carpenter\Components\Column
     */
    public function column($name)
    {
        $this->columns[$name] = new Column($name, $this->key, $this->drivers);
        $this->columns[$name]->setLabel(ucwords(str_replace('_', ' ', $name)));

        return $this->columns[$name];
    }

    /**
     * Add a new action to the table.
     *
     * @param string $name
     * @param string $position
     * @return \Michaeljennings\Carpenter\Components\Action
     */
    public function action($name, $position = 'table')
    {
        $this->actions[$position][$name] = new Action($name);

        return $this->actions[$position][$name];
    }

    /**
     * Set the amount to paginate the table by.
     *
     * @param string|integer $amount
     * @return $this;
     */
    public function paginate($amount)
    {
        $this->paginate = $amount;

        return $this;
    }

    /**
     * Set the model to be used by the table. Can be either the model name or
     * an instance of the model.
     *
     * @param mixed $model
     * @return $this
     */
    public function model($model)
    {
        $this->drivers->store->model(new $model);

        return $this;
    }

    /**
     * Add a new filter to be run on the results.
     *
     * @param callable $filter
     * @return $this
     */
    public function filter(Closure $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * Render the table to a string.
     *
     * @return string
     */
    public function render()
    {
        // TODO: Implement render() method.
    }

    protected function prepareRows()
    {
        // TODO: Prepare the table rows ready to be displayed.
    }

    /**
     * Return all of the table's rows.
     *
     * @return array
     */
    public function rows()
    {
        if (empty($this->rows)) {
            $this->prepareRows();
        }

        return $this->rows;
    }

    /**
     * Alias of the rows method.
     *
     * @return array
     */
    public function getRows()
    {
        return $this->rows();
    }

    /**
     * Check if the table has any rows.
     *
     * @return boolean
     */
    public function hasRows()
    {
        return ! empty($this->rows);
    }

    /**
     * Return all of the table's columns.
     *
     * @return array
     */
    public function columns()
    {
        return $this->columns;
    }

    /**
     * Alias of the columns method.
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns();
    }

    /**
     * Check if there are any table columns
     *
     * @return boolean
     */
    public function hasColumns()
    {
        return ! empty($this->columns);
    }

    /**
     * Get the actions with a position of table.
     *
     * @return array
     */
    public function actions()
    {
        return isset($this->actions['table']) ? $this->actions['table'] : [];
    }

    /**
     * Alias for the actions method.
     *
     * @return array
     */
    public function getActions()
    {
        return $this->actions();
    }

    /**
     * Check if there are any table actions.
     *
     * @return boolean
     */
    public function hasActions()
    {
        return ! empty($this->actions['table']);
    }

    /**
     * Set the template for this table instance.
     *
     * @param string $template
     * @return $this
     */
    public function template($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Alias for the template method.
     *
     * @param string $template
     * @return $this;
     */
    public function setTemplate($template)
    {
        return $this->template($template);
    }

    /**
     * Set the table title.
     *
     * @param string $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Alias for the title method.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        return $this->title($title);
    }

    /**
     * Return the table title.
     *
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the url the table actions post to.
     *
     * @param string $action
     * @return $this
     */
    public function formAction($action)
    {
        $this->formAction = $action;

        return $this;
    }

    /**
     * Alias for the form action method.
     *
     * @param string $action
     * @return $this
     */
    public function setFormAction($action)
    {
        return $this->formAction($action);
    }

    /**
     * Return the form action.
     *
     * @return null|string
     */
    public function getFormAction()
    {
        return $this->formAction;
    }

    /**
     * Set the method for the table form to use.
     *
     * @param $method
     * @return $this
     */
    public function formMethod($method)
    {
        $this->formMethod = $method;

        return $this;
    }

    /**
     * Alias for the formMethod method.
     *
     * @param $method
     * @return Table
     */
    public function setFormMethod($method)
    {
        return $this->formMethod($method);
    }

    /**
     * Return the form method.
     *
     * @return string
     */
    public function getFormMethod()
    {
        return $this->formMethod;
    }

    /**
     * Get the table links
     *
     * @return string
     */
    public function links()
    {
        return $this->links;
    }

    /**
     * Alias for the links method.
     *
     * @return string
     */
    public function getLinks()
    {
        return $this->links();
    }

    /**
     * Check if there are any table links.
     *
     * @return boolean
     */
    public function hasLinks()
    {
        return ! empty($this->links);
    }

    /**
     * Set the results to be displayed.
     *
     * @param $data
     * @return TableContract
     */
    public function results($data)
    {
        // TODO: Implement results() method.
    }

    /**
     * Alias of the results method.
     *
     * @param $data
     * @return TableContract
     */
    public function setResults($data)
    {
        // TODO: Implement setResults() method.
    }

    /**
     * Change a driver to another supported driver.
     *
     * @param $type
     * @param $driver
     * @return TableContract
     */
    public function driver($type, $driver)
    {
        // TODO: Implement driver() method.
    }

    /**
     * Change the store driver.
     *
     * @param $driver
     * @return TableContract
     */
    public function store($driver)
    {
        // TODO: Implement store() method.
    }

    /**
     * Change the session driver.
     *
     * @param $driver
     * @return $this
     */
    public function session($driver)
    {
        // TODO: Implement session() method.
    }

    /**
     * Change the paginator driver.
     *
     * @param $driver
     * @return TableContract
     */
    public function paginator($driver)
    {
        // TODO: Implement paginator() method.
    }

    /**
     * Change the view driver.
     *
     * @param $driver
     * @return TableContract
     */
    public function view($driver)
    {
        // TODO: Implement view() method.
    }


}