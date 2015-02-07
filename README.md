# Carpenter
An laravel 4 package for creating html tables from models quickly and easily.
##Installation
Include the package in your `composer.json`.

    "michaeljennings/carpenter": "dev-master";

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

Publish the config files using `php artisan config:publish michaeljennings/carpenter`

Publish the pacakge assets using `php artisan asset:publish michaeljennings/carpenter`

By default the tables are store in a app/tables.php so you may need to create this file. Alternatively if
you want to store your tables elsewhere you can update the path in the package config.

## Usage

### Creating Tables

There are two ways to create tables, the first is using the `add` method. Like with the `routes.php` file we use a 
`tables.php` to have a convinient place to store our carpenter instances, this allows us to keep our controllers 
clean. To create a new table we can use the `add` method, the first paramater is a unique key for the table the second is 
a closure used for creating the table.

    Carpenter::add('foo', function($table) {});
    
This will store the table in a collection and we can retrieve it later using the `get` method.

    Carpenter::get('foo');
    
If you don't want to use the collection you can use the `create` method to immediately create the table.
    
    Carpenter::create('foo', function($table) {});
    
To render the table to html we use the `render` method.

    Carpenter::get('foo')->render();
    
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
    
The column function returns a new column object and we can chain methods onto this, for example the label method will 
change the label at the top of the column.

    $table->column('foo')->label('Bar');
    
Also if you need to format the data displayed in a column we can use the `presenter` function. This takes a closure
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
    $table->action('edit', 'row'); // I'll be in each row

Like with the columns we can then chain functions on to the actions. By default the button will be a submit button, 
but you can change it to an anchor by using the href method. The href function can be passed a url or a closure 
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
    
If you need to add a confirmed popup for the button you can do so by adding a confirmed method. To use the confirmed 
function you will need to include the carpenter.js file and run the jQuery plugin.

    $table->action('foo')->confirmed("I have to be confirmed first.");
    
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
