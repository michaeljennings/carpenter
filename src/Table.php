<?php namespace Michaeljennings\Carpenter;

use Closure;
use Michaeljennings\Carpenter\Components\Row;
use Michaeljennings\Carpenter\Components\Cell;
use Michaeljennings\Carpenter\Nexus\Container;
use Michaeljennings\Carpenter\View\ViewManager;
use Michaeljennings\Carpenter\Components\Action;
use Michaeljennings\Carpenter\Components\Column;
use Michaeljennings\Carpenter\Store\StoreManager;
use Michaeljennings\Carpenter\Session\SessionManager;
use Michaeljennings\Carpenter\Pagination\PaginationManager;
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
     * An instance of the carpenter store manager.
     *
     * @var StoreManager
     */
    protected $store;

    /**
     * An instance of the carpenter session manager.
     *
     * @var SessionManager
     */
    protected $session;

    /**
     * An instance of the carpenter view manager.
     *
     * @var ViewManager
     */
    protected $view;

    /**
     * An instance of the carpenter pagination manager.
     *
     * @var PaginationManager
     */
    protected $paginator;

    /**
     * The carpenter config.
     *
     * @var array
     */
    protected $config = [];

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
     * The column to sort the table by.
     *
     * @var string|null
     */
    protected $sortBy;

    /**
     * The direction to order results.
     *
     * @var string|null
     */
    protected $sortDir;

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

    /**
     * The name of the wrapper for each table row.
     *
     * @var string
     */
    protected $wrapper;

    public function __construct(
        $key,
        StoreManager $store,
        SessionManager $session,
        ViewManager $view,
        PaginationManager $paginator,
        array $config
    ) {
        $this->key = $key;
        $this->store = $store;
        $this->session = $session;
        $this->view = $view;
        $this->paginator = $paginator;
        $this->config = $config;

        $this->setOrderValues();
    }

    /**
     * Add a new column to the table.
     *
     * @param string $name
     * @return \Michaeljennings\Carpenter\Components\Column
     */
    public function column($name)
    {
        $this->columns[$name] = $this->newColumn($name, $this->key, $this->session, $this->config);

        // Check if the user is trying to access a nested element, if they are set the
        // label to the last element
        if (strpos($name, '.') !== false) {
            $parts = explode('.', $name);
            $label = array_pop($parts);
        } else {
            $label = $name;
        }

        $this->columns[$name]->setLabel(ucwords(str_replace('_', ' ', $label)));

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
        $this->actions[$position][$name] = $this->newAction($name);

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
        $this->store->model(new $model);

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

        return $this->view->make($this->template, array(
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
            $this->columns['option'] = new Column(false, $this->key, $this->session, $this->config);
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
            $row->cell($key, $this->newCell($this->getCellValue($result, $key), $result, $column));
        }

        if ( ! empty($this->actions['row'])) {
            foreach ($this->prepareRowActions($this->actions['row'], $result) as $action) {
                $row->action($action);
            }
        }

        return $row;
    }

    /**
     * Get the value to be displayed in a cell.
     *
     * @param $result
     * @param $key
     * @return mixed
     */
    protected function getCellValue($result, $key)
    {
        if (strpos($key, '.') !== false) {
            $keys = explode('.', $key);
            $value = $result;

            foreach ($keys as $key) {
                $value = $value->$key;
            }
        } else {
            $value = $result->$key;
        }

        return $value;
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
            $action = clone $action;

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
                $filter($this->store);
            }
        }

        $this->orderResults();

        // Check if the results need to be paginated or not
        if ( ! $this->paginate) {
            $this->results = $this->newContainer($this->store->results());
        } else {
            $this->paginator->make($this->store->count(), $this->paginate);
            $this->links = $this->paginator->links();

            $this->results = $this->newContainer(
                $this->store->paginate($this->paginate, $this->paginator->currentPage(), $this->paginate)
            );
        }
    }

    /**
     * Check if any of the column links have been clicked and order the results
     * by that column if needed.
     */
    protected function orderResults()
    {
        if ($this->session->has($this->config['session']['key'] . '.'. $this->key.'.sort')) {
            $this->sortBy = $this->session->get($this->config['session']['key'] . '.'.$this->key.'.sort');
            if ($this->session->has($this->config['session']['key'] . '.'.$this->key.'.dir')) {
                $this->sortDir = 'desc';
            }
        }

        if (isset($this->sortBy)) {
            // Remove any orders from the query and order by the selected column
            $this->store->refreshOrderBy();
            if (isset($this->sortDir)) {
                $this->store->orderBy($this->sortBy, $this->sortDir);
            } else {
                $this->store->orderBy($this->sortBy, 'asc');
            }
        }
    }

    /**
     * Create a new column.
     *
     * @param string $name
     * @param string $key
     * @param SessionManager $session
     * @param array $config
     * @return Column
     */
    protected function newColumn($name, $key, SessionManager $session, array $config)
    {
        return new Column($name, $key, $session, $config);
    }

    /**
     * Return a new table cell.
     *
     * @param mixed $value
     * @param mixed $result
     * @param Column $column
     * @return Cell
     */
    protected function newCell($value, $result, Column $column)
    {
        return new Cell($value, $result, $column);
    }

    /**
     * Return a new table action.
     *
     * @param string $name
     * @return Action
     */
    protected function newAction($name)
    {
        return new Action($name);
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
        $this->store->data($data);

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
        $this->store->driver($driver);

        return $this;
    }

    /**
     * Change the wrapper class to be used for each table row.
     *
     * @param $wrapper
     * @return $this
     */
    public function wrapper($wrapper)
    {
        $this->wrapper = $wrapper;

        return $this;
    }

    /**
     * Get the name of the wrapper class for each table row.
     *
     * @return string
     */
    protected function getWrapper()
    {
        return isset($this->wrapper) ? $this->wrapper: $this->config['store']['wrapper'];
    }

    /**
     * Create a new container instance.
     *
     * @param array $data
     * @return Container
     */
    protected function newContainer(array $data)
    {
        return new Container($data, $this->config, $this->getWrapper());
    }

    /**
     * Check if the user has ordered the table and which direction it is
     * ordered.
     */
    protected function setOrderValues()
    {
        if (isset($_GET['sort'])) {
            $this->session->put($this->config['session']['key'] . '.' . $this->key . '.sort', $_GET['sort']);
            if (isset($_GET['dir'])) {
                $this->session->put($this->config['session']['key'] . '.' . $this->key . '.dir', true);
            } else {
                $this->session->forget($this->config['session']['key'] . '.' . $this->key . '.dir');
            }
        }
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