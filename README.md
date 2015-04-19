# Carpenter
A PHP package to create HTML tables from a data store that can be sorted and paginated.
##Installation
Include the package in your `composer.json`.

    "michaeljennings/carpenter": "~0.2"

Run `composer install` or `composer update` to download the dependencies.

Once the package has been downloaded add the validation service provider to the list of service providers 
in `app/config/app.php`.

    'providers' => array(

      'Michaeljennings\Carpenter\CarpenterServiceProvider'
  
    );
    
Add the `Carpenter` facade to your aliases array.

    'aliases' => array(

      'Carpenter' => 'Michaeljennings\Carpenter\Facades\Carpenter',
      
    );

Publish the config files using `php artisan vendor:publish --provider="Michaeljennings\Carpenter\CarpenterServiceProvider"`

## Usage

### Creating Tables

There are two ways to create tables with carpenter. Firstly we can use the table collection where you add a table 
somewhere in your application to the table collection and the retrieve it later. Or we can create a one off instance of
a table.

#### Using the Table Collection

To add a class to the table collection we use the `add` method. The `add` method takes two arguments. The first is a 
unique key for the table that we shall use to retrieve the table and the second is either a closure or the name of a  
class.

    Carpenter::add('foo', function($table) {});
    Carpenter::add('bar', 'FooBar');    

By default the table based classes use a build method, but if you wish to specify a method you can do so by using an @ 
symbol then the name of the method.

    Carpenter::add('bar', 'FooBar@bar');
    
To retrieve the table from the collection we use the `get` method.

    Carpenter::get('foo');
    
#### Tables Without the Collection

To create a table without using the table collection we use the `make` method. This takes the same arguments as the 
`add` method as the unique key is used for to help keep session unique to the table.

    Carpenter::make('foo', function($table) {});
    Carpenter::make('bar', 'FooBar');

#### Rendering the Table

To render the table instance you can either echo out the table or use the `render` method to turn it into a string.

    Carpenter::get('foo')->render();
    
#### Using Dependency Injection

If you would rather use dependency injection instead of the facade you may do so by type hinting the 
`Michaeljennings\Carpenter\Contracts\Carpenter` contract.

    public function foo(Michaeljennings\Carpenter\Contracts\Carpenter $carpenter)
    {
        return $carpenter->get('foo')->render();
    }
    
### Table Methods

To set the model to be used for the table we use the `model` method.

    Carpenter::add('foo', function($table)
    {
      $table->model('Bar');
    });

By default the title for the table will be set to the name of the model, to overwrite this use the `setTitle` method.

    Carpenter::add('foo', function($table)
    {
      $table->model('Bar');
      $table->setTitle('Foo Bar');
    });
    
To paginate the results use the paginate function.

    Carpenter::add('foo', function($table)
    {
      $table->model('Bar');
      $table->paginate(15);
    });
    
#### Table Columns    

To create a new table column we use the `column` method.

    $table->column('foo');
    
The column function returns a new column object and we can chain methods onto this, for example the `setLabel` method 
will change the label at the top of the column.

    $table->column('foo')->setLabel('Bar');
    
If you need to format the data displayed in a column we can use the `presenter` function. This takes a closure
and runs it over each cell in that column, for example if we have a column which is putting out a date we could 
format it to make it more user friendly.

    $table->column('date')->presenter(function($date)
    {
        $date = new DateTime($date);
        return $date->format('d-m-Y');
    });
    
If you need to access data from within the row not just the value of the cell you are using the presenter you can 
do so using the second parameter in the presenter closure.

    $table->column('bar')->presenter(function($bar, $row)
    {
        if ($row->foo) {
            return $bar;
        }
    });
    
#### Table Actions

To add buttons to the table we can use the `action` method. There are two places you can position the buttons, along 
the top and in each row. You can set the position by passing a second parameter to the action method of either 
`'table'` or `'row'`, the buttons default to being along the top if no parameter is supplied.

    $table->action('create', 'table'); // I'll be at the top of the table
    $table->action('edit', 'row'); // I'll be at the end of each row

Like with the columns we can then chain functions on to the actions. By default the button will be a submit button, 
but you can change it to an anchor by using the href method. The href function can be passed a string or a closure 
which will give the id of the row as a first paramater and the whole row as the second.

    $table->action('foo')->href('/bar');
    
    $table->action('edit', 'row')->href(function($id) 
    {
      return route('edit', [$id]); 
    });
    
    $table->action('view', 'row')->href(function($id, $row) 
    {
      return route('view', [$row->slug]) 
    });

To set a label for the action we can use the `setLabel` method.

    $table->action('foo')->setLabel('Bar');
    
To add a class to the action we use the `setClass` method.

    $table->action('foo')->setClass('bar');
    
You can also set any html attribute by using the attribute name as the method name.

    $table->action('foo')->id('bar');
    
If you need to add a confirmed popup for the button you can do so by adding a confirmed method.

    $table->action('foo')->confirmed("I have to be confirmed first.");
    
To use the confirmed function you will need to include the carpenter.js file and run the jQuery plugin.
    
    <script type="text/javascript" src="/path/to/script/carpenter.js"></script>
    <script type="text/javascript">
        $('.table-parent').carpenterJs();
    </script>
    
It may also be useful to only show an action when a condition is met, to do this we use the `when` method. This can 
only be used for actions that are in a row. The when method takes one parameter which is a closure which has the 
current row passed to it.

    $table->action('foo')->when(function($row)
    {
      if ($row->status == 'bar') {
        return true;
      }
      
      return false;
    });
    
#### Filters

Filters are used to filter the results show in the table. You can use any of the query builder functions in the 
filter.

    $table->filter(function($q)
    {
      $q->orderBy('foo');
    });
    
You can use the filters until the table is rendered so if you need to use the same table in multiple places but 
filter it slightly differently you can do so.

    Carpenter::get('foo')->filter(function($q)
    {
      $q->where('foo', '=', 'bar');
    });
