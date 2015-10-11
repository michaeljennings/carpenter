# Carpenter [![Build Status](https://travis-ci.org/michaeljennings/carpenter.svg?branch=master)](https://travis-ci.org/michaeljennings/carpenter) [![Latest Stable Version](https://poser.pugx.org/michaeljennings/carpenter/v/stable)](https://packagist.org/packages/michaeljennings/carpenter) [![Coverage Status](https://coveralls.io/repos/michaeljennings/carpenter/badge.svg?branch=master&service=github)](https://coveralls.io/github/michaeljennings/carpenter?branch=master) [![License](https://poser.pugx.org/michaeljennings/carpenter/license)](https://packagist.org/packages/michaeljennings/carpenter)

Carpenter is a PHP package to make creating HTML tables from a collection of data a breeze. 

It also handles paginating, sorting, and makes tables reusable throughout your application.

## Documentation

For full documentation check out [carpenter.michaeljennings.im](http://carpenter.michaeljennings.im/).

## Contents

- [Installation](#installation)
- [Laravel 5 Integration](#laravel-5-integration)
- [Laravel 4 Integration](#laravel-4-integration)
- [Creating a Table Instance](#creating-a-table-instance)
- [Table Markup](#table-markup)
	- [Class Based Tables](#class-based-tables)
- [Setting Table Data](#setting-table-data)
	- [Paginating Results](#paginating-results)
- [Adding Columns](#adding-columns)
	- [Sorting Columns](#sorting-columns)
	- [Column Labels](#column-labels)
	- [Formatting Column Data](#formatting-column-data)
- [Adding Actions](#adding-actions)
	- [Set the Action Link](#set-action-link)
	- [Set the Action Label](#set-action-label)
	- [Set the Action Attributes](#setting-action-attributes)
	- [Confirm an Action](#confirm-an-action)
- [Filtering Table Data](#filtering-table-data)
- [Rendering Tables](#rendering-tables)
	- [Rendering With a Template](#rendering-with-a-template) 
	- [Getting Data From a Table Instance](#getting-data-from-a-table-instance)

## Installation
This package requires PHP 5.4+, and includes a Laravel 5 Service Provider and Facade.

To install through composer include the package in your `composer.json`.

    "michaeljennings/carpenter": "1.0.*"

Run `composer install` or `composer update` to download the dependencies or you can run `composer require michaeljennings/carpenter`.

## Laravel 5 Integration

To use the package with Laravel 5 firstly add the carpenter service provider to the list of service providers 
in `app/config/app.php`.

    'providers' => array(

      'Michaeljennings\Carpenter\CarpenterServiceProvider'
  
    );
    
Add the `Carpenter` facade to your aliases array.

    'aliases' => array(

      'Carpenter' => 'Michaeljennings\Carpenter\Facades\Carpenter',
      
    );

Publish the config files using `php artisan vendor:publish --provider="Michaeljennings\Carpenter\CarpenterServiceProvider"`

To access carpenter you can either use the Facade or the carpenter instance is bound to the IOC container and you can 
then dependency inject it via its contract.

```php
Carpenter::get('foo');

public function __construct(Michaeljennings\Carpenter\Contracts\Carpenter $carpenter)
{
    $this->carpenter = $carpenter;
}
```

## Laravel 4 Integration

To use the package with Laravel 4 firstly add the carpenter service provider to the list of service providers 
in `app/config/app.php`.

    'providers' => array(

      'Michaeljennings\Carpenter\CarpenterServiceProvider'
  
    );
    
Add the `Carpenter` facade to your aliases array.

    'aliases' => array(

      'Carpenter' => 'Michaeljennings\Carpenter\Facades\Carpenter',
      
    );

Publish the config files using `php artisan config:publish michaeljennings/carpenter"`

To access carpenter you can either use the Facade or the carpenter instance is bound to the IOC container and you can 
then dependency inject it via its contract.

```php
Carpenter::get('foo');

public function __construct(Michaeljennings\Carpenter\Contracts\Carpenter $carpenter)
{
    $this->carpenter = $carpenter;
}
```

## Creating a Table Instance

To get started creating tables firstly you want to make a table instance. There are two different ways to go about this.

Firstly Carpenter allows you to bind a tabke to a key and then retrieve it when you need it.

To bind an instance use the `add`.

```php
$carpenter->add('foo', function($table) {});
```

You can then retrieve and instance by using the `get` method.

```php
$table = $carpenter->get('foo');
```

Once you've retrieved a table you can run any of the table methods below to alter the table. Or if you wish you can 
pass a closure as a second parameter and alter the table in there.

```php
$table = $carpenter->get('foo');

$table->column('bar');

$table = $carpenter->get('foo', function($table) {
	$table->column('bar');
});

```

However if you wish to make a single table instance without binding the table then you can use the `make` method.

```php
$table = $carpenter->make('foo', function($table) {});
```

With the `make` method the key passed as the first argument is not used to bind the table, but it is used to keep all
session data unique to the table.

## Table Markup

Now that you have your table instance it's time to start defining the markup of your table. To this you may either pass
an annonymous function to either the `add` or `make` method or pass it the name of class to use.

```php
$carpenter->add('foo', function($table) {
	// Table logic goes here.
});

$carpenter->add('bar', Bar::class);
```

### Class Based Tables

By default when using a class to define your table markup you need to provide a build method. This will be passed the 
table instance.

```php
use Michaeljennings\Carpenter\Contracts\Table;

class Bar 
{
	public function build(Table $table)
	{
		//
	}
}
```

If you want to use a different method name then you just need to specify it when you create your table instance by 
seperating the class name and method name with an @.

```php
$carpenter->add('bar', "Bar@table");

class Bar
{
	public function table(Table $table)
	{
		//
	}
}
```

## Setting Table Data

At present you can either use Eloquent or CodeIgniter models, or multidimensional arrays to populate the table.

To set the model to be used use the `model` method.

```php
$table->model(FooModel::class);
```

Or to use an array pass the array to the `data` method.

```php
$table->data($data);
```

### Paginating Results

To paginate the table pass the amount of results you wish to show on each page to the `paginate` method.

```php
$table->paginate(15);
```

## Adding Columns

To add a new column or retrieve an existing column from the table use the `column` method.

```php
$table->column('foo');
```

### Sorting Columns

By default all columns are set to be sortable and this will be handled by carpenter. However if you wish to stop a 
column from being sortable the use the `unsortable` method. Or if you wish to make the column sortable use the 
`sortable` method.

```php
$table->column('foo')->sortable();
$table->column('foo')->unsortable();
```

When sorting the package will attempt to sort by the column key, however if you are accessing a column that doesn't 
exist in your data store this can cause issues. An example of this would be if you were accessing a mutator in laravel.

To help get around this you can use the `sort` method to set a custom closure to be used to sort the column. The
closure will be passed two parameters; the data store you are querying, and whether the sort is in descending order.

```php
$table->column('foo')->sort(function($query, $desc) {
	if ($desc) {
	 	$query->orderBy('bar', 'desc');
	} else {
		$query->orderBy('bar', 'asc');
	}
});
```

### Column Labels

By default the column will work out the label to use for the column from the column name. If the column name has an 
underscore then it will replace it with a space and then it will capitalise all words.

If you want to specify a specific label you can use the `setLabel` method.

```php
$table->column('foo'); // Label: Foo
$table->column('foo_bar'); // Label: Foo Bar
$table->column('foo_bar')->setLabel('Baz') // Label: Baz
```

### Formatting Column Data

Occasionally you may wish to format each value in a column. For example if you were showing the price of an item then
you may want to format to a currency. To do this you can use a presenter.

To get started use the `presenter` method. This is passed an annonymous function for you to format the value.

```php
$table->column('price')->presenter(function($value) {
	return '&' . number_format($value, 2);
});
```

You may also wish to get data from the reset of the row. For example you may only want to show the price if the item
is set to online. To do this just pass a second parameter to the closure.

```php
$table->column('price')->presenter(function($value, $row) {
	if ($row->online) {
		return '&' . number_format($value, 2);
	}
});
```

## Adding Actions

Occasionally you may need to add buttons or links to each row, or to the top of the table. To do this we use actions.

To add an action to the table, or retrieve and existing action, use the `action` method. The first parameter is a key 
for the action and the second is the position in the table. By default the actions go to the top of the table but to 
put them at the end of the row pass the position as the second argument.

```php
$table->action('create');
$table->action('edit', 'row');
```

By default actions are set as buttons, however if you set an href attribute it will become an anchor. Or you can use the
`setTag` method to set a custom element. When you set an element don't pass any chevrons as this will be added when the 
action is rendered.

```php
$table->action('create')->setTag('div');
```

In the default templates the table is wrapped in a form which is where the actions post to. By default the form posts to
the current url you are on, however if you wish to post to a specific url you can use the `setFormAction` method. Also
if you don't want the form to post you can set the method using the `setFormMethod` method.

```php
$table->setFormAction('/search');
$table->setFormMethod('GET');
```

### Set Action Link

To set a url for the action use the `setHref` method.

```php
$table->action('create')->setHref('/create');
```

If you are using a row action you may also want to access data from the row. To this pass a closure to the `setHref` 
method.

```php
$table->action('edit', 'row')->setHref(function($id) {
	return '/edit/' . $id;
});

$table->action('edit', 'row')->setHref(function($id, $row) {
	return '/edit/' . $row->slug;
});
```

### Set Action Label

To set the label for an action use the `setLabel` method.

```php
$table->action('edit', 'row')->setLabel('Edit');
```

### Setting Action Attributes

To add a class to the action use the `setClass` method.

```php
$table->action('edit', 'row')->setClass('btn');
```

To set other attributes you can either use the attribute name as the method name, or use the `setAttribute` method.

```php
$table->action('edit', 'row')->id('edit-item');
$table->action('edit', 'row')->setAttribute('id', 'edit-item');
```

Again like with the `setHref` method you can pass a closure as the value to get attributes from the row.

```php
$table->action('edit', 'row')->setAttribute('data-id', function($id, $row) {
	return $id;
});
```

### Confirm an Action

For some actions, like deletes you may want the user to confirm that they want to run the action. To this you can use 
the `confirmed` method. This will leverage the default JavaScript confirm method.

The `confirmed` method takes one parameter; either the text you want the confirm box to show, or if you are using a row 
action then you can pass a closure to access properties from the table row.

```php
$table->action('delete', 'table')->confirmed('Are you use you to delete that?');
$table->action('delete', 'row')->confirmed(function($id, $row) {
	return "Are you sure you want to delete {$row->name}?";
});
```

This will then add a confirmed attribute to the action.

	<button confirmed="Are you use you to delete that?"></button>

Carpenter comes with a jQuery plugin to handle the confirmed functionality or you can listen for the attribute. To use
the plugin just include the carpenter.js file and then run the `carpenterJs()` method on the parent element around the
table.

    <script type="text/javascript" src="/path/to/script/carpenter.js"></script>
    <script type="text/javascript">
        $('.table-parent').carpenterJs();
    </script>
    
## Other Table Methods

### Set a Table Title

To set a title for the table use the `setTitle` method. In the default templates this will be shown to the top left of 
the table.

```php
$table->setTitle('FooBar Table');
```

## Filtering Table Data

Occasionally you may find your self wanting to query the data in the tables. To do this you can use filters.

You can add a filter either when you are creating the table markup or after you have retrieved the table instance.

To use the filter pass a closure to the `filter` method and the closure will be passed an instance of the data store
you are using. If you are using a model you can use any of the methods you have access to on that model.


```php
$table->filter(function($q) {
	$q->orderBy('foo');
});

$table->get('foo')->filter(function($q) {
	$q->where('foo', '=', 'bar');
});
```

## Rendering Tables

There are two ways to render tables; you can either use a template, or you can get the data from the table instance.

### Rendering With a Template

To render a table using a template call the `render` method on the table instance.

```php
$table->render();
```

By default when you call the render method it will use the template set in the config file. If you want to use a 
template for a specific table then use the `setTemplate` method, or you can pass through a template when you call the
`render` method. You can also pass through any data to the template as an array when you call the `render` method. If
you want to pass through data but you the default template then just pass through null instead of the path to the 
template.

```php
$table->setTemplate('path/to/template.php');

$table->render('path/to/template.php');
$table->render('path/to/template.php', ['foo' => 'bar']);
$table->render(null, ['foo' => 'bar']);
```

Currently Carpenter supports Laravel, CodeIgniter and a native PHP template renderer's. You can set which renderer to 
use in the config file.
 
Carpenter also comes with three default templates, but it's very simple to make your own.

### Getting Data From a Table Instance

### Getting the Table Columns

To get the table columns use the `getColumns()` method.

```php
foreach ($table->getColumns() as $column) {
	//
}
```

#### Column Methods

Get the column HTML attributes.

```php
$table->getAttributes();
```

Get the column heading label.

```php
$table->getLabel();
```

Check if the column is sortable.

```php
$table->isSortable();
```

Get the column href.

```php
$table->getHref();
```

### Getting the Table Rows

To get the rows use the `getRows` method.

```php
foreach ($table->getRows() as $row) {
	//
}
```

You can also check if the table has any rows with the `hasRows` method.

```php
if ($table->hasRows()) {
	//
}
```

#### Row Methods

To get the cells in the row use the `getCells` method.

```php
foreach ($row->getCells() as $cell) {
	echo $cell->value;
}
```

To check if the row has any actions use the `hasActions` method.

```php
if ($row->hasActions()) {
	//
}
```

Then to get the actions for the row use the `getActions` method. Finally to render the action use the `render` method on
the action.

```php
foreach ($row->getActions() as $action) {
	echo $action->render();
}
```

### Getting Table Actions

To check if the table has any table actions use the `hasActions` method.

```php
if ($table->hasActions()) {
	//
}
```

To get the actions to play at the top of the table use the `getActions` method. Then to render the action use the 
`render` method on the action.

```php
foreach ($table->getActions() as $action) {
	echo $action->render();
}
```

### Getting the Pagination Links

To check if the table has any pagination links use the `hasLinks` method.

```php
if ($table->hasLinks()) {
	//
}
```

Then to get the pagination links use the `getLinks` method.

```php
echo $table->getLinks();
```

### Other Table Methods

#### Getting the Table Title

To get the title use the `getTitle` method.

```php
$table->getTitle();
```

#### Getting the Form Action

To get the form action use the `getFormAction` method.

```php
$table->getFormAction();
```

Also to get the form method use the `getFormMethod` method.

```php
$table->getFormMethod();
```
