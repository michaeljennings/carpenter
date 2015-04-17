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
     * A flag indicating if the rows have been set up yet.
     *
     * @var bool
     */
    protected $rowsInitialised = false;

    /**
     * The results to be displayed.
     *
     * @var mixed
     */
    protected $results;

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
     * @var string|null
     */
    protected $template;

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

        if (isset($_GET['sort'])) {
            $this->drivers->session->put($this->config['session']['key'].'.'.$this->key.'.sort', $_GET['sort']);
            if (isset($_GET['dir'])) {
                $this->drivers->session->put($this->config['session']['key'].'.'.$this->key.'.dir', true);
            } else {
                $this->drivers->session->forget($this->config['session']['key'].'.'.$this->key.'.dir');
            }
        }
    }

    /**
     * Add a new column to the table.
     *
     * @param string $name
     * @return \Michaeljennings\Carpenter\Components\Column
     */
    public function column($name)
    {
        $this->columns[$name] = new Column($name, $this->key, $this->drivers, $this->config);
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
        $this->rows();

        if ( ! isset($this->template)) {
            $this->template = $this->config['view']['views']['template'];
        }

        return $this->drivers->view->make($this->template, array(
            'table' => $this
        ));
    }

    /**
     * Return all of the table's rows.
     *
     * @return array
     */
    public function rows()
    {
        if ( ! $this->rowsInitialised) {
            $this->prepareRows();
        }

        return $this->rows;
    }

    /**
     * Loop through all of the table results and prepare them to be displayed.
     */
    protected function prepareRows()
    {
        if ( ! isset($this->results)) {
            $this->generateResults();
        }

        foreach ($this->results as $result) {

            $this->rows[] = $this->newRow($result);
        }

        if ( ! empty($this->actions['row']) && ! isset($this->columns['option'])) {
            $this->columns['option'] = new Column(false, $this->key, $this->drivers, $this->config);
        }

        $this->rowsInitialised = true;
    }

    /**
     * Create a new row and all of it's cells and actions.
     *
     * @param $result
     * @return Row
     */
    protected function newRow($result)
    {
        $row = new Row;

        if ( ! empty($result->id)) {
            $row->id = $result->id;
        }

        foreach ($this->columns as $key => $column) {
            $row->cell($key, new Cell($result->$key, $result, $column));
        }

        if ( ! empty($this->actions['row'])) {
            foreach ($this->prepareRowActions($this->actions['row'], $result) as $action) {
                $row->action($action);
            }
        }

        return $row;
    }

    /**
     * Loop through all of the row actions and add any necessary row data.
     *
     * @param array $rowActions
     * @param mixed $result
     * @return array
     */
    protected function prepareRowActions(array $rowActions, $result)
    {
        $actions = [];

        foreach ($rowActions as $action) {
            if ($action->valid($result)) {
                $column = $action->getColumn();
                $action->value = $result->$column;
                $action->row($result);

                $actions[] = $action;
            }
        }

        return $actions;
    }

    /**
     * Generate the results to be displayed in the table if none have been set.
     */
    protected function generateResults()
    {
        // Run the filters on the store driver
        if ( ! empty($this->filters)) {
            foreach ($this->filters as $filter) {
                $filter($this->drivers->store);
            }
        }

        $this->orderResults();

        // Check if the results need to be paginated or not
        if ( ! $this->paginate) {
            $this->results = $this->newContainer($this->drivers->store->results());
        } else {
            $this->drivers->paginator->make($this->drivers->store->count(), $this->paginate);
            $this->links = $this->drivers->paginator->links();

            $this->results = $this->newContainer(
                $this->drivers->store->paginate($this->paginate, $this->drivers->paginator->currentPage())
            );
        }
    }

    /**
     * Check if any of the column links have been clicked and order the results
     * by that column if needed.
     */
    protected function orderResults()
    {
        if ($this->drivers->session->has($this->config['session']['key'] . '.'.$this->key.'.sort')) {
            $this->sortBy = $this->drivers->session->get($this->config['session']['key'] . '.'.$this->key.'.sort');
            if ($this->drivers->session->has($this->config['session']['key'] . '.'.$this->key.'.dir')) {
                $this->sortDir = 'desc';
            }
        }

        if (isset($this->sortBy)) {
            // Remove any orders from the query and order by the selected
            // column
            $this->drivers->store->refreshOrderBy();
            if (isset($this->sortDir)) {
                $this->drivers->store->orderBy($this->sortBy, $this->sortDir);
            } else {
                $this->drivers->store->orderBy($this->sortBy, 'asc');
            }
        }
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
    public function getActions()
    {
        return isset($this->actions['table']) ? $this->actions['table'] : [];
    }

    /**
     * Alias for the getActions method.
     *
     * @return array
     */
    public function actions()
    {
        return $this->getActions();
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
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Alias for the setTemplate method.
     *
     * @param string $template
     * @return $this;
     */
    public function template($template)
    {
        return $this->setTemplate($template);
    }

    /**
     * Set the table title.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Alias for the setTitle method.
     *
     * @param string $title
     * @return $this
     */
    public function title($title)
    {
        return $this->setTitle($title);
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
    public function setFormAction($action)
    {
        $this->formAction = $action;

        return $this;
    }

    /**
     * Alias for the setFormAction method.
     *
     * @param string $action
     * @return $this
     */
    public function formAction($action)
    {
        return $this->setFormAction($action);
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
    public function setFormMethod($method)
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
    public function formMethod($method)
    {
        return $this->setFormMethod($method);
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
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Alias for the getLinks method.
     *
     * @return string
     */
    public function links()
    {
        return $this->getLinks();
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
     * Set the data to be displayed.
     *
     * @param array $data
     * @return \Michaeljennings\Carpenter\Table
     */
    public function data(array $data)
    {
        // Ensure the array driver is selected.
        $this->store('array');
        $this->drivers->store->data($data);

        return $this;
    }

    /**
     * Change the store driver.
     *
     * @param $driver
     * @return \Michaeljennings\Carpenter\Table
     */
    public function store($driver)
    {
        $this->drivers->store->driver($driver);

        return $this;
    }

    /**
     * Create a new container instance.
     *
     * @param array $data
     * @return Container
     */
    protected function newContainer(array $data)
    {
        return new Container($data);
    }

    /**
     * Render the table to a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}