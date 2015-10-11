<?php

namespace Michaeljennings\Carpenter;

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

class Table implements TableContract
{
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
     * The total results from the query.
     *
     * @var int
     */
    protected $total;

    /**
     * Check if the table is being sorted.
     *
     * @var bool|null
     */
    protected $sorted;

    /**
     * Check if the table is being sorted in descending order.
     *
     * @var bool
     */
    protected $descending;

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
        if ( ! isset($this->columns[$name])) {
            $this->columns[$name] = $this->newColumn($name, $this->key);

            // Check if the user is trying to access a nested element, if they are set the
            // label to the last element
            if (strpos($name, '.') !== false) {
                $parts = explode('.', $name);
                $label = array_pop($parts);
            } else {
                $label = $name;
            }

            $this->columns[$name]->setLabel(ucwords(str_replace('_', ' ', $label)));
        }

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
        if ( ! isset($this->actions[$position][$name])) {
            $this->actions[$position][$name] = $this->newAction($name);
        }

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
        if (is_string($model)) {
            $model = new $model;
        }

        $this->store->model($model);

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
     * Render the table to a string. Optionally pass a template to use, and data
     * to be passed to the template.
     *
     * @param string|null $template
     * @param array       $data
     * @return string
     */
    public function render($template = null, $data = [])
    {
        $this->rows();

        if ($template) {
            $this->template = $template;
        } elseif ( ! isset($this->template)) {
            $this->template = $this->config['view']['views']['template'];
        }

        $data['table'] = $this;

        return $this->view->make($this->template, $data);
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
            $this->columns['option'] = $this->newColumn(false, $this->key);
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

        if (isset($result->id)) {
            $row->id = $result->id;
        }

        foreach ($this->columns as $key => $column) {
            $row->cell($key, $this->newCell($column, $this->getCellValue($result, $key), $result));
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

                $action->value($result->$column);
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
        $this->total = $this->store->count();

        // Check if the results need to be paginated or not
        if ( ! $this->paginate) {
            $this->results = $this->newContainer($this->store->results());
        } else {
            $this->paginator->make($this->total, $this->paginate);
            $this->links = $this->paginator->links();

            $this->results = $this->newContainer(
                $this->store->paginate($this->paginate, $this->paginator->currentPage(), $this->paginate)
            );
        }
    }

    /**
     * Check if any of the columns are being sorted. If so unset all of the current
     * order by's and then sort by the selected column.
     */
    protected function orderResults()
    {
        $this->setSortParameters();

        if (isset($this->sortBy)) {
            // Remove any orders from the query and order by the selected column
            $this->store->refreshOrderBy();

            $column = $this->columns[$this->sortBy];

            // Check if the column has a custom sort
            if ($column->hasSort()) {
                $callback = $column->getSort();

                $callback($this->store, $column->isDescending());
            } else {
                if (isset($this->sortDir)) {
                    $this->store->orderBy($this->sortBy, $this->sortDir);
                } else {
                    $this->store->orderBy($this->sortBy, 'asc');
                }
            }
        }
    }

    /**
     * Check if a column is being sorted, if so then set the sort column in the session
     * and check if the it is in descending order.
     */
    protected function setSortParameters()
    {
        if ($this->isSorted()) {
            $this->sortBy = $this->session->get($this->config['session']['key'] . '.' . $this->key . '.sort');
            if ($this->isDescending()) {
                $this->sortDir = 'desc';
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
     * Get the total results from the query.
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Get the total amount being displayed per page.
     *
     * @return int|string|null
     */
    public function getTotalPerPage()
    {
        if ($this->paginate) {
            return $this->paginate;
        }
    }

    /**
     * Check if the table is sorted.
     *
     * @return bool
     */
    public function isSorted()
    {
        if ( ! isset($this->sorted)) {
            $this->sorted = $this->session->has($this->config['session']['key'] . '.' . $this->key . '.sort');
        }

        return $this->sorted;
    }

    /**
     * Check if the table is being sorted in descending order.
     *
     * @return bool
     */
    public function isDescending()
    {
        if ( ! isset($this->descending)) {
            $this->descending = $this->session->has($this->config['session']['key'] . '.' . $this->key . '.dir');
        }

        return $this->descending;
    }

    /**
     * Create a new container instance.
     *
     * @param array $data
     * @return Container
     */
    protected function newContainer(array $data)
    {
        return new Container($data, $this->config, $this->store->getWrapper());
    }

    /**
     * Create a new column.
     *
     * @param string $name
     * @param string $key
     * @return Column
     */
    protected function newColumn($name, $key)
    {
        return new Column($name, $key, $this->session, $this->config);
    }

    /**
     * Return a new table cell.
     *
     * @param Column $column
     * @param mixed  $result
     * @param mixed  $value
     * @return Cell
     */
    protected function newCell(Column $column, $value, $result)
    {
        return new Cell($column, $value, $result);
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
     * Check if the user has ordered the table and which direction it is
     * ordered.
     */
    protected function setOrderValues()
    {
        if (isset($_GET['sort']) && isset($_GET['table']) && $_GET['table'] == urlencode($this->key)) {
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