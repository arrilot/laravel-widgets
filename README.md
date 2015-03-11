[![Build Status](https://travis-ci.org/Arrilot/laravel-widgets.svg?branch=2.0)](https://travis-ci.org/Arrilot/laravel-widgets)

#Widgets for Laravel

*This packages provides widget functionality to boost your Laravel views. Includes asynchronous mode and generator.*

## Installation

First, use some composer awesomeness:

```composer require arrilot/laravel-widgets```

Note: for Laravel 4 use  ```composer require arrilot/laravel-widgets ~1.0```

Then, register a service provider in your `app.php` config file

```php
<?php

'providers' => [
    ...
    'Arrilot\Widgets\ServiceProvider',
],
?>
```

Finally, add facades here too.

```php
<?php

'aliases' => [
    ...
    'Widget'       => 'Arrilot\Widgets\Facade',
    'AsyncWidget'  => 'Arrilot\Widgets\AsyncFacade',
],
?>
```

## Usage

Lets consider we want to make a list of recent news and reuse it in several views.

First of all we can create a Widget using artisan command provided by the package.
```bash
php artisan make:widget RecentNews
```

Now the folowing widget skeleton is created:
```php
<?php namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class RecentNews extends AbstractWidget {

    /**
    * You can treat this method just like a controller action.
    * Return a view or anything you want to display
    */
	public function run()
	{

	}
}
```

As soon as domain logic is implemented inside the `run()` method, the widget can be included to a view like that:
```php
{!! Widget::recentNews() !!}
```
Make sure the widget class can be autoloaded by composer.

## Configuration

### Namespaces configuration
By default package tries to find your widget in the ```App\Widgets``` namespace.

You can override this by changing `default_namespace` property in the package config.

Althought using the default namespace is very convenient and keeps you from doing unnecessary actions, you can also set custom namespaces for specific widgets:
```php
    'custom_namespaces_for_specific_widgets' => [
        'widgetName' => 'Widget\Namespace\Here'
        ....
    ]
```

Note: do not forget to publish package config before making these changes.

Laravel 5:
```bash
php artisan vendor:publish
```

Laravel 4:
```bash
php artisan config:publish arrilot/laravel-widgets
```

### Widget configuration

#### Using config array

Let's carry on with the "recent news" example.

Imagine that we usually need to show 5 news, but in some views we need to show 10.
This can be easily achieved like that:

```php
class RecentNews extends AbstractWidget {
    ...
    protected $count = 5;
    ...
}

...
{!! Widget::recentNews() !!}
...
{!! Widget::recentNews(['count' => 10]) !!}
```
`['count' => 10]` is a config array.
Notice that you don't need to map a config array and class properties in constructor. It's done automatically behind the scene

#### Using additional parameters

You can also choose to pass additional parameters to the `run()` function directly if you like it.

```php
{!! Widget::recentNews([], 'date', 'asc') !!}
{!! Widget::recentNews(['count' => 10], 'date', 'asc') !!}
...
public function run($sort_by, $sort_order) { }
...
```

`run()` method is resolved via Laravel service container so you can use method injection here too.

## Asynchronous widgets

In some situations it can be very beneficial to load widget content with AJAX.

Fortunately this can be achieved very easily!

All you need to do is to swap `Widget::` facade for `AsyncWidget::` facade when you call a widget and make sure you have jquery loaded for ajax calls.

If asynchronous mode causes you any problems you can always change it back.

