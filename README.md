[![Latest Stable Version](https://poser.pugx.org/arrilot/laravel-widgets/v/stable.svg)](https://packagist.org/packages/arrilot/laravel-widgets/)
[![Total Downloads](https://img.shields.io/packagist/dt/arrilot/laravel-widgets.svg?style=flat)](https://packagist.org/packages/arrilot/laravel-widgets)
[![Build Status](https://img.shields.io/travis/arrilot/laravel-widgets/master.svg?style=flat)](https://travis-ci.org/arrilot/laravel-widgets)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/arrilot/laravel-widgets/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/arrilot/laravel-widgets/)

#Widgets for Laravel

*A powerful alternative to view composers. Asynchronous widgets, reloadable widgets, console generator, caching - everything you can think of.*

### For Laravel 4, please use the [1.0 branch](https://github.com/Arrilot/laravel-widgets/tree/1.0)!

> Note: This is the doc for the latest stable release. If you need documentation for your specific version you can find it by clicking on a corresponding tag here https://github.com/Arrilot/laravel-widgets/releases

## Installation

1) Run ```composer require arrilot/laravel-widgets```

2) Register a service provider in the `app.php` configuration file

```php
<?php

'providers' => [
    ...
    Arrilot\Widgets\ServiceProvider::class,
],
?>
```

3) Add some facades here too. If you prefer custom blade directives instead of facades (see later) you can skip it.

```php
<?php

'aliases' => [
    ...
    'Widget'       => Arrilot\Widgets\Facade::class,
    'AsyncWidget'  => Arrilot\Widgets\AsyncFacade::class,
],
?>
```

## Usage

Let's consider we want to make a list of recent news and reuse it in several views.

First of all we can create a Widget class using the artisan command provided by the package.
```bash
php artisan make:widget RecentNews
```
This command generates two files:

1) `resources/views/widgets/recent_news.blade.php` is an empty view. 

Add "--plain" option if you do not need a view.

2) `app/Widgets/RecentNews` is a widget class.

```php
<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class RecentNews extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //

        return view("widgets.recent_news", [
            'config' => $this->config,
        ]);
    }
}
```

> Note: You can use your own stubs if you need. Publish config file to change paths.

The last step is to call the widget.
You've actually got several ways to do so.

```php
{{ Widget::run('recentNews') }}
```
or
```php
{{ Widget::recentNews() }}
```
or even
```php
@widget('recentNews')
```

There is no real difference between them. The choice is up to you.

> Note: For Laravel 5.0.0 - 5.1.3 you have to use `{!! !!}` tags instead of `{{ }}`

## Passing variables to widget

### Via config array

Let's carry on with the "recent news" example.

Imagine that we usually need to show *five* news, but in some views we need to show *ten*.
This can be easily achieved like that:

```php
class RecentNews extends AbstractWidget {
    ...
    protected $config = [
        'count' => 5
    ];
    
    ...
}

...
@widget('recentNews') // shows 5
...
@widget('recentNews', ['count' => 10]) // shows 10
```
`['count' => 10]` is a config array that can be accessed by $this->config.

> Note: Config fields that are not specified when you call a widget aren't overridden:

```php
class RecentNews extends AbstractWidget {
    ...
    protected $config = [
        'count' => 5,
        'foo'   => 'bar'
    ];
    
    ...
}

@widget('recentNews', ['count' => 10]) // $this->config['foo'] is still 'bar'
```

Config array is available in every widget method so you can use it to configure placeholder and container too (see below)

### Directly

You can also choose to pass additional parameters to `run()` method directly.

```php
@widget('recentNews', ['count' => 10], 'date', 'asc')
...
public function run($sort_by, $sort_order) { }
...
```

`run()` method is resolved via Service Container, so method injection is available here too.

## Namespaces

By default the package tries to find your widget in the ```App\Widgets``` namespace.

You can override this by publishing package config and setting `default_namespace` property.

Publish config command - ```php artisan vendor:publish --provider="Arrilot\Widgets\ServiceProvider"```

Although using the default namespace is very convenient, in some situations you may wish to have more flexibility. 
For example, if you've got dozens of widgets it makes sense to group them in namespaced folders.

No problem, you have several ways to call those widgets:

1) Pass a full widget name from the `default_namespace` (basically `App\Widgets`) to the `run` method.
```php
@widget('News\RecentNews', $config)
{{ Widget::run('News\RecentNews', $config) }}
```

2) Use dot notation.
```php
@widget('news.recentNews', $config)
{{ Widget::run('news.recentNews', $config) }}
```

3) FQCN is also an option.
```php
@widget('\App\Http\Some\Namespace\Widget', $config)
{{ Widget::run('\App\Http\Some\Namespace\Widget', $config) }}
```

## Asynchronous widgets

In some situations it can be very beneficial to load widget content with AJAX.

Fortunately, this can be achieved very easily!
All you need to do is to change facade or blade directive - `Widget::` => `AsyncWidget::`, `@widget` => `@asyncWidget`

> Note: Since version 3.1 you no longer need `jquery` to make ajax calls. However you can set `use_jquery_for_ajax_calls` to `true` in the config file if you need for some reason.

By default nothing is shown until ajax call is finished.

This can be customized by adding a `placeholder()` method to the widget class.

```php
public function placeholder()
{
    return "Loading...";
}
```

> Note: If you need to do smth with the routes package uses to load async widgets (e.g. you run app in a subfolder http://site.com/app/) you need to copy Arrilot\Widgets\ServiceProvider to your app, modify it according to your needs and register it in Laravel instead of the former one.

## Reloadable widgets

You can go even further and automatically reload widget every N seconds.

Just set the `$reloadTimeout` property of the widget class and you are done.

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

Both sync and async widgets can become reloadable.

You should use this feature with care, because it can easily spam your app with ajax calls if timeouts are too low.
Consider using web sockets too but they are waaaay harder to set up on the other hand.

## Container

Async and Reloadable widgets both require some DOM interaction so they wrap all widget output in a html container.
This container is defined by AbstractWidget::container() method and can be customized therefore.

```php
    /**
     * Async and reloadable widgets are wrapped in container.
     * You can customize it by overriding this method.
     *
     * @return array
     */
    public function container()
    {
        return [
            'element'       => 'div',
            'attributes'    => 'style="display:inline" class="arrilot-widget-container"',
        ];
    }
```

> Note: Nested async or reloadable widgets are not supported because of container id collision.

## Caching

There is also a simple built-in way to cache entire widget output.
Just set $cacheTime property in your widget class and you are done.

```php
class RecentNews extends AbstractWidget
{
    /**
     * The number of minutes before cache expires.
     * False means no caching at all.
     *
     * @var int|float|bool
     */
    public $cacheTime = 60;
}
```

No caching is turned on by default.
A cache key depends on a widget name and each widget parameter.
Override ```cacheKey``` method if you need to adjust it.

## Widget groups (extra)

In most cases Blade is a perfect tool for setting the position and order of widgets.
However, in some cases you may find useful the approach with widget groups:

```php
// add several widgets to the 'sidebar' group anywhere you want (even in controller)
Widget::group('sidebar')->position(5)->addWidget(<the same arguments list as in run() method>);
Widget::group('sidebar')->position(4)->addAsyncWidget(<the same arguments list as in run() method>);

// display them in a view in the correct order
{{ Widget::group('sidebar')->display() }}
//or 
@widgetGroup('sidebar')
```

`position()` can be omitted from the chain.

`Widget::group('sidebar')->addWidget('files');` 

equals

`Widget::group('sidebar')->position(100)->addWidget('files');`

You can also set a separator to display between widgets in a group.
`Widget::group('sidebar')->setSeparator('<hr>');` 

### Checking the state of a widget group

`Widget::group('sidebar')->isEmpty(); // bool`

`Widget::group('sidebar')->any(); // bool`

`Widget::group('sidebar')->count(); // int`
