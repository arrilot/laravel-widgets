[![Total Downloads](https://img.shields.io/packagist/dt/Arrilot/laravel-widgets.svg?style=flat)](https://packagist.org/packages/Arrilot/laravel-widgets)
[![Build Status](https://img.shields.io/travis/Arrilot/laravel-widgets/master.svg?style=flat)](https://travis-ci.org/Arrilot/laravel-widgets)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/Arrilot/laravel-widgets/master.svg?style=flat)](https://scrutinizer-ci.com/g/Arrilot/laravel-widgets/)
[![MIT License](https://img.shields.io/packagist/l/Arrilot/laravel-widgets.svg?style=flat)](https://packagist.org/packages/Arrilot/laravel-widgets)

#Widgets for Laravel

*This package provides widget functionality to boost your Laravel views. It also includes asynchronous widgets, reloadable widgets, generator and etc.*

### For Laravel 4, please use the [1.0 branch](https://github.com/Arrilot/laravel-widgets/tree/1.0)!

## Installation

1) Run ```composer require arrilot/laravel-widgets```

2) Register a service provider in the `app.php` configuration file

```php
<?php

'providers' => [
    ...
    'Arrilot\Widgets\ServiceProvider',
],
?>
```

3) Add some facades here too.

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

Let's consider we want to make a list of recent news and reuse it in several views.

First of all we can create a Widget class using the artisan command provided by the package.
```bash
php artisan make:widget RecentNews --view
```
This command generates two files:

1) `resources/views/widgets/recent_news.blade.php` is an empty view. 

Omit "--view" option if you do not need it.

2) `app/Widgets/RecentNews` is a widget class.

```php
<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class RecentNews extends AbstractWidget
{
    /**
     * Treat this method as a controller action.
     * Return a view or other content to display.
     */
    public function run()
    {
        //

        return view("widgets.recent_news");
    }
}
```

The last step is to call the widget.
You've actually got several ways to do so.

```php
{!! Widget::run('recentNews') !!}
```
or
```php
{!! Widget::recentNews() !!}
```
or even
```php
@widget('recentNews')
```

## Configuration

### Widget configuration

#### Using config array

Let's carry on with the "recent news" example.

Imagine that we usually need to show *five* news, but in some views we need to show *ten*.
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
Notice that you don't need to map the config array and class properties in constructor. It's done automatically behind the scenes.

#### Using additional parameters

You can also choose to pass additional parameters to the `run()` method directly if you like it.

```php
{!! Widget::recentNews([], 'date', 'asc') !!}
{!! Widget::recentNews(['count' => 10], 'date', 'asc') !!}
{!! Widget::run('recentNews', ['count' => 10], 'date', 'asc') !!}
@widget('recentNews', ['count' => 10], 'date', 'asc')
...
public function run($sort_by, $sort_order) { }
...
```

`run()` method is resolved via Laravel service container so method injection is available here too.

### Namespaces configuration

By default package tries to find your widget in the ```App\Widgets``` namespace.

You can overwrite this by publishing package config and setting `default_namespace` property.

Although using the default namespace is very convenient and keeps you from doing unnecessary actions, in some situations you may wish to have more flexibility. 
For example, if you've got dozens of widgets it makes sense to group them in namespaced folders.

You actually have several ways to call those widgets:

1) You can pass the full name to the `run` method.
```php
{!! Widget::run('News\RecentNews', $config) !!}
@widget('News\RecentNews', $config)
```

2) You can use dot notation instead.
```php
{!! Widget::run('news.recentNews', $config) !!}
@widget('news.recentNews', $config)
```

3) Finally, you can register a widget in package config like that.
```php
    'custom_namespaces_for_specific_widgets' => [
        'recentNews' => 'App\Widgets\News'
        ....
    ]
```
and then call it without namespaces
```php
{!! Widget::recentNews($config) !!}
{!! Widget::run('recentNews', $config) !!}
@widget('recentNews', $config)
```

## Asynchronous widgets

In some situations it can be very beneficial to load widget content with AJAX.

Fortunately, this can be achieved very easily!

1. Make sure you have jquery loaded for ajax calls before the widget is called.
2. Change facade or blade directive - `Widget::` => `AsyncWidget::`, `@widget` => `@asyncWidget`

Done.

By default nothing is shown until ajax call is finished.

This can be easily customized by adding a `placeholder()` method to the widget class.

```php
public function placeholder()
{
	return "Loading...";
}
```

## Reloadable widgets

You can go even further and automatically reload widget every N seconds.

To achieve that:

1. Make sure you have jquery loaded for ajax calls before the widget is called.
2. Set the `$reloadTimeout` property of the widget class.

```php
class RecentNews extends AbstractWidget
{
    /**
     * The number of seconds before each reload.
     *
     * @var int|float
     */
    public $reloadTimeout = 10;
}
```
Done.

Both sync and async widgets can become reloadable.

You should use this feature with care, because it can easily spam your app with ajax calls if timeouts are too low.
Consider using web sockets too but they are waaaay harder to set up on the other hand.


## Widget groups (extra)

In most cases Blade is a perfect tool fot setting the position and order of widgets.
However, in some cases you may find useful the approach with widget groups.
Please check the following example:

```php
// add several widgets to the 'sidebar' group anywhere you want (even in controller)
Widget::group('sidebar')->position(5)->addWidget(<the same arguments list as in run() method>);
Widget::group('sidebar')->position(4)->addAsyncWidget(<the same arguments list as in run() method>);

// display them in a view in the correct order
{!! Widget::group('sidebar')->display() !!}
//or 
@widgetGroup('sidebar')
```

`position()` can be omitted from the chain.

`Widget::group('sidebar')->addWidget('files');` 

equals

`Widget::group('sidebar')->position(100)->addWidget('files');`
