# Carpenter [![Build Status](https://travis-ci.org/michaeljennings/carpenter.svg?branch=master)](https://travis-ci.org/michaeljennings/carpenter) [![Latest Stable Version](https://poser.pugx.org/michaeljennings/carpenter/v/stable)](https://packagist.org/packages/michaeljennings/carpenter) [![Latest Unstable Version](https://poser.pugx.org/michaeljennings/carpenter/v/unstable)](https://packagist.org/packages/michaeljennings/carpenter) [![License](https://poser.pugx.org/michaeljennings/carpenter/license)](https://packagist.org/packages/michaeljennings/carpenter)

A PHP package to create HTML tables from a data store that can be sorted and paginated.

- [Planned Features](#planned-features)
- [Installation](#installation)
- [Laravel 5 Integration](#laravel-5-integration)
- [Laravel 4 Integration](#laravel-4-integration)
- [Creating a Table Instance](#creating-a-table-instance)
- [Table Markup](#table-markup)
	- [Class Based Tables](#class-based-tables)
- [Setting Table Data](#setting-table-data)
	- [Paginating Results](#paginating-data)
- [Adding Columns](#adding-columns)
	- [Sorting Columns](#sorting-columns)
	- [Column Labels](#column-labels)
	- [Formatting Column Data](#formatting-column-data)
- [Adding Actions](#adding-actions)
	- [Set the Action Link](#set-the-action-link)
	- [Set the Action Label](#set-the-action-label)
	- [Set the Action Attributes](#set-the-action-attributes)
	- [Confirm an Action](#confirm-an-action)
- [Filtering Table Data](#filtering-table-data)

## Planned Features
- Unit Tests & CI
- PDO Store Driver
- Search Table Results

## Installation
This package requires PHP 5.4+, and includes a Laravel 5 Service Provider and Facade.

To install through composer include the package in your `composer.json`.

    "michaeljennings/carpenter": "~0.2"

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

public function __construct(Michaeljennings\Carpenter\Contracts\Table $carpenter)
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

public function __construct(Michaeljennings\Carpenter\Contracts\Table $carpenter)
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

To add an action to the table use the `action` method. The first parameter is a key for the action and the second is 
the position in the table. By default the actions go to the top of the table but to put them at the end of the row pass
the position as the second argument.

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
the `confirmed` method. Again if you want to access attributes from the row you can pass a closure as the value.

```php
$table->action('delete', 'row')->confirmed('Are you use you to delete that?');
$table->action('delete', 'row')->confirmed(function($id, $product) {
	return "Are you sure you want to delete {$product->name}?";
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
