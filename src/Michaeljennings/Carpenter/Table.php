<?php namespace Michaeljennings\Carpenter;

use Str;
use Closure;
use Michaeljennings\Carpenter\Components\Row;
use Michaeljennings\Carpenter\Components\Cell;
use Michaeljennings\Carpenter\Components\Action;
use Michaeljennings\Carpenter\Components\Column;
use Michaeljennings\Carpenter\Exceptions\ModelNotSetException;

class Table {

    /**
     * A unique key for the table to allow us to keep searching and sorting
     * separate for each table.
     *
     * @var string
     */
    protected $key;

    /**
     * An object containing all required drivers to create a table.
     *
     * @var DriverContainer
     */
    protected $drivers;

    /**
     * The name of the model we will be getting results from.
     *
     * @var string
     */
    protected $model;

    /**
     * The title at the top of the table, if no title is set the name of the
     * model will be used.
     *
     * @var string
     */
    protected $title;

    /**
     * Set the amount to paginate the table by.
     *
     * @var bool|string
     */
    protected $paginate = false;

    /**
     * The table columns.
     *
     * @var array
     */
    protected $columns = array();

    /**
     * The table rows
     *
     * @var array
     */
    protected $rows = array();

    /**
     * The table actions
     *
     * @var array
     */
    protected $actions = array();

    /**
     * An array of closures to be run on the model
     *
     * @var array
     */
    protected $filters = array();

    /**
     * The table results
     *
     * @var mixed
     */
    protected $results;

    /**
     * The pagination links
     *
     * @var mixed
     */
    protected $links;

    /**
     * The table view
     *
     * @var string
     */
    protected $template;

    /**
     * The url the table actions will post to
     *
     * @var string
     */
    protected $formAction;

    public function __construct($key, Closure $table, $drivers, $config)
    {
        $this->key = $key;
        $this->drivers = $drivers;
        $this->config = $config;

        if (isset($_GET['sort'])) {
            $this->drivers->session->put('michaeljennings.carpenter.'.$this->key.'.sort', $_GET['sort']);
            if (isset($_GET['dir'])) {
                $this->drivers->session->put('michaeljennings.carpenter.'.$this->key.'.dir', true);
            } else {
                $this->drivers->session->forget('michaeljennings.carpenter.'.$this->key.'.dir');
            }
        }

        $table($this);
    }

    /**
     * Set the name of the model we shall be getting results for.
     *
     * @param string $model
     */
    public function model($model)
    {
        $this->model = $model;

        if ( ! isset($this->title)) {
            $this->title = $model;
        }
    }

    /**
     * Set the amount to paginate the table results by.
     *
     * @param string|integer $amount
     */
    public function paginate($amount)
    {
        $this->paginate = $amount;
    }

    /**
     * Create a new table column
     *
     * @param  string $name
     * @return Michaeljennings\Carpenter\Componenets\Column
     */
    public function column($name)
    {
        $this->columns[$name] = new Column($name, $this->key, $this->drivers);
        $this->columns[$name]->label = Str::title($name);

        return $this->columns[$name];
    }

    /**
     * Create a new table action
     *
     * @param  string $name
     * @param  string $position The position on the table, can be 'table' or 'row'
     * @return Michaeljennings\Carpenter\Components\Action
     */
    public function action($name, $position = 'table')
    {
        $this->actions[$position][$name] = new Action($name);

        return $this->actions[$position][$name];
    }


    /**
     * Add a new filter to the filters array
     *
     * @param callable $filter
     */
    public function filter(Closure $filter)
    {
        $this->filters[] = $filter;
    }

    /**
     * Run any stored filters and get/paginate the results from the selected model.
     *
     * @throws ModelNotSetException
     */
    public function results()
    {
        if ( ! isset($this->model)) {
            throw new ModelNotSetException("You must set a model to get results from.");
        }

        $this->drivers->db->setModel(new $this->model);

        // Run the filters on the database driver
        if ( ! empty($this->filters)) {
            foreach ($this->filters as $filter) {
                $filter($this->drivers->db);
            }
        }

        $this->orderResults();

        // Check if the results need to be paginated or not
        if ( ! $this->paginate) {
            $this->results = $this->drivers->db->results();
        } else {
            $this->drivers->paginator->make($this->drivers->db->count(), $this->paginate);
            $this->links = $this->drivers->paginator->links();
            $this->results = $this->drivers->db->paginate($this->paginate);
        }
    }

    /**
     * Check if any results have been set, if not get the results from the model
     * then loop through the results to prepare them for being rendered.
     */
    protected function rows()
    {
        if ( ! isset($this->results)) {
            $this->results();
        }

        foreach ($this->results as $result) {
            $row = new Row;
            if ( ! empty($result->id)) {
                $row->id = $result->id;
            }

            foreach ($this->columns as $key => $column) {
                $row->cells[$key] = new Cell($result->$key, $result, $key, $column);
            }

            if ( ! empty($this->actions['row'])) {
                $actions = '';
                foreach ($this->actions['row'] as $action) {
                    if ($action->valid($result)) {
                        $column = $action->getColumn();
                        $action->value = $result->$column;
                        $actions .= $action->render();
                    }
                }
                $row->cells[] = new Cell($actions);
            }

            $this->rows[] = $row;
        }

        if (!empty($this->actions['row'])) {
            $this->columns['option'] = new Column(false, $this->key, $this->drivers);
        }
    }

    /**
     * Check if any of the column links have been clicked and order the results
     * by that column if needed.
     */
    private function orderResults()
    {
        if ($this->drivers->session->has('michaeljennings.carpenter.'.$this->key.'.sort')) {
            $this->sortBy = $this->drivers->session->get('michaeljennings.carpenter.'.$this->key.'.sort');
            if ($this->drivers->session->has('michaeljennings.carpenter.'.$this->key.'.dir')) {
                $this->sortDir = 'desc';
            }
        }

        if (isset($this->sortBy)) {
            // Remove any orders from the query and order by the selected
            // column
            $this->drivers->db->refreshOrderBy();
            if (isset($this->sortDir)) {
                $this->drivers->db->orderBy($this->sortBy, $this->sortDir);
            } else {
                $this->drivers->db->orderBy($this->sortBy, 'asc');
            }
        }
    }

    /**
     * Generate the table rows and return the table view.
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
     * Set the unique key for this table
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Set the template for this table instance
     *
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * Set the table title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Set the url the table actions post to
     *
     * @param string $action
     */
    public function setFormAction($action)
    {
        $this->formAction = $action;
    }

    /**
     * Set the database field to search for results in
     *
     * @param string $key
     */
    public function setSearchKey($key)
    {
        $this->searchKey = $key;
    }

    /**
     * Return the table title
     *
     * @return string
     */
    public function getTitle()
    {
        return empty($this->title) ? Str::title($this->model) : $this->title;
    }

    /**
     * Check if there are any rows
     *
     * @return boolean
     */
    public function hasRows()
    {
        return ! empty($this->rows);
    }

    /**
     * Get the table row
     *
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Check if there are any table actions
     *
     * @return boolean
     */
    public function hasActions($postition)
    {
        return ! empty($this->actions[$postition]);
    }

    /**
     * Get the table actions
     *
     * @return array
     */
    public function getActions($postition)
    {
        return ! empty($this->actions[$postition]) ? $this->actions[$postition] : false;
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
     * Get the table columns
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Check if there are any table links
     *
     * @return boolean
     */
    public function hasLinks()
    {
        return ! empty($this->links);
    }

    /**
     * Get the table links
     *
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Check if any search terms have been set
     *
     * @return boolean
     */
    public function hasSearchTerms()
    {
        return ! empty($this->searchTerms);
    }

    /**
     * Retrieve the search terms
     *
     * @return string
     */
    public function getSearchTerms()
    {
        return $this->searchTerms;
    }

    /**
     * Check if a form action has been set
     *
     * @return boolean
     */
    public function hasFormAction()
    {
        return ! empty($this->formAction);
    }

    /**
     * Get the form action
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->formAction;
    }
} 