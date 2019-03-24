[![Latest Stable Version](https://poser.pugx.org/arrilot/laravel-widgets/v/stable.svg)](https://packagist.org/packages/arrilot/laravel-widgets/)
[![Total Downloads](https://img.shields.io/packagist/dt/arrilot/laravel-widgets.svg)](https://packagist.org/packages/arrilot/laravel-widgets)
[![Build Status](https://img.shields.io/travis/arrilot/laravel-widgets/master.svg)](https://travis-ci.org/arrilot/laravel-widgets)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/arrilot/laravel-widgets/master.svg)](https://scrutinizer-ci.com/g/arrilot/laravel-widgets/)

# Widgets for Laravel

*A powerful alternative to view composers. Asynchronous widgets, reloadable widgets, console generator, caching - everything that you can imagine.*

## Installation

Run ```composer require arrilot/laravel-widgets```

Laravel >=5.5 uses Package Auto-Discovery, so you don't need to manually add the ServiceProvider and Facades

## Usage

Let's consider that we want to make a list of recent news and reuse it in several views.

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

        return view('widgets.recent_news', [
            'config' => $this->config,
        ]);
    }
}
```

> Note: You can use your own stubs if you need. Publish config file to change paths.

The last step is to call the widget.
There are several ways to do so.

```php
@widget('recentNews')
```
or
```php
{{ Widget::run('recentNews') }}
```
or even
```php
{{ Widget::recentNews() }}
```

There is no real difference between these. The choice is up to you.

## Passing variables to widget

### Via config array

Let's carry on with the "recent news" example.

Imagine that we usually need to show *five* news, but in some views we need to show *ten*.
This can be easily achieved by:

```php
class RecentNews extends AbstractWidget
{
    ...
    protected $config = [
        'count' => 5
    ];
    ...
}

...
@widget('recentNews') // shows 5
@widget('recentNews', ['count' => 10]) // shows 10
```
`['count' => 10]` is a config array that can be accessed by $this->config.

Config array is available in every widget method so you can use it to configure placeholder and container too (see below)

> Note: Config fields that are not specified when you call a widget aren't overridden:

```php
class RecentNews extends AbstractWidget
{
    ...
    protected $config = [
        'count' => 5,
        'foo'   => 'bar'
    ];
    
    ...
}

@widget('recentNews', ['count' => 10]) // $this->config['foo'] is still 'bar'
```

> Note 2: You may want (but you probably don't) to create your own BaseWidget and inherit from it.
That's fine. The only edge case is merging config defaults from a parent and a child. 
In this case do the following:

1) Do not add `protected $config = [...]` line to a child.

2) Instead add defaults like this:

```php
public function __construct(array $config = [])
{
    $this->addConfigDefaults([
        'child_key' => 'bar'
    ]);

    parent::__construct($config);
}
```

### Directly

You can also choose to pass additional parameters to `run()` method directly.

```php
@widget('recentNews', ['count' => 10], 'date', 'asc')
...
public function run($sortBy, $sortOrder) { }
...
```

`run()` method is resolved via Service Container, so method injection is also available here.

## Namespaces

By default the package tries to find your widget in the ```App\Widgets``` namespace.

You can override this by publishing package config (```php artisan vendor:publish --provider="Arrilot\Widgets\ServiceProvider"```) and setting `default_namespace` property.

Although using the default namespace is very convenient, in some cases you may wish to have more flexibility. 
For example, if you've got dozens of widgets it makes sense to group them in namespaced folders.

No problem, there are several ways to call those widgets:

1) Pass a full widget name from the `default_namespace` (basically `App\Widgets`) to the `run` method.
```php
@widget('News\RecentNews', $config)
```

2) Use dot notation.
```php
@widget('news.recentNews', $config)
```

3) FQCN is also an option.
```php
@widget('\App\Http\Some\Namespace\Widget', $config)
```

## Asynchronous widgets

In some situations it can be very beneficial to load widget content with AJAX.

Fortunately, this can be achieved very easily!
All you need to do is to change facade or blade directive - `Widget::` => `AsyncWidget::`, `@widget` => `@asyncWidget`

Widget params are encrypted (by default) and sent via ajax call under the hood. So expect them to be `json_encoded()` and `json_decoded()` afterwards.

> Note: You can turn encryption off for a given widget by setting `public $encryptParams = false;` on it. However, this action will make widget params publicly accessible, so please make sure to not leave any vulnerabilities.
For example, if you pass something like user_id through widget params and turn encryption off, you do need to add one more access check inside the widget.

> Note: You can set `use_jquery_for_ajax_calls` to `true` in the config file to use it for ajax calls if you want to.

By default nothing is shown until ajax call is finished.

This can be customized by adding a `placeholder()` method to the widget class.

```php
public function placeholder()
{
    return 'Loading...';
}
```

> Side note: If you need to do something with the routes package used to load async widgets (e.g. you run app in a subfolder http://site.com/app/) you need to copy Arrilot\Widgets\ServiceProvider to your app, modify it according to your needs and register it in Laravel instead of the former one.

## Reloadable widgets

You can go even further and automatically reload widget every N seconds.

Just set the `$reloadTimeout` property of the widget class and it is done.

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
Consider using web sockets too but they are way harder to set up.

## Container

Async and Reloadable widgets both require some DOM interaction so they wrap all widget output in a html container.
This container is defined by `AbstractWidget::container()` method and can be customized too.

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

> Note: Nested async or reloadable widgets are not supported.

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

### Cache tagging

When tagging is supported ([see the Laravel cache documentation](https://laravel.com/docs/cache#cache-tags)) and to 
simplify cache flushing, a tag `widgets` is assigned by default to all widgets. 
You can define one or more additional tags to your widgets by setting the values 
in the `$cacheTags` property in your widget class. Example :

```php
class RecentNews extends AbstractWidget
{
    /**
     * Cache tags allow you to tag related items in the cache 
     * and then flush all cached values that assigned a given tag.
     *
     * @var array
     */
    public $cacheTags = ['news', 'frontend'];
}
```

For this example, if you need to flush :

```php
// Clear widgets with the tag news
Cache::tags('news')->flush();

// Clear widgets with the tag news OR backend
Cache::tags(['news', 'frontend'])->flush();

// Flush all widgets cache
Cache::tags('widgets')->flush();
```

## Widget groups (extra)

In most cases Blade is a perfect tool for setting the position and order of widgets.
However, sometimes you may find useful the following approach:

```php
// add several widgets to the 'sidebar' group anywhere you want (even in controller)
Widget::group('sidebar')->position(5)->addWidget('widgetName1', $config1);
Widget::group('sidebar')->position(4)->addAsyncWidget('widgetName2', $config2);

// display them in a view in the correct order
@widgetGroup('sidebar')
// or 
{{ Widget::group('sidebar')->display() }}
```

`position()` can be omitted from the chain.

`Widget::group('sidebar')->addWidget('files');` 

equals

`Widget::group('sidebar')->position(100)->addWidget('files');`

You can set a separator that will be display between widgets in a group.
`Widget::group('sidebar')->setSeparator('<hr>')->...;`

You can also wrap each widget in a group using `wrap` method like that.
```php
Widget::group('sidebar')->wrap(function ($content, $index, $total) {
    // $total is a total number of widgets in a group.
    return "<div class='widget-{$index}'>{$content}</div>";
})->...;
```

### Removing widgets from a group

There is a couple of ways to remove widget/widgets from a group after they've been already added.

1) Remove one widget by its unique `id`
```php
$id1 = Widget::group('sidebar')->addWidget('files');
$id2 = Widget::group('sidebar')->addAsyncWidget('files');
Widget::group('sidebar')->removeById($id1); // There is only second widget in the group now
```

2) Remove all widgets with specific name
```php
Widget::group('sidebar')->addWidget('files');
Widget::group('sidebar')->addAsyncWidget('files');
Widget::group('sidebar')->removeByName('files'); // Widget group is empty now
```

3) Remove all widgets that are placed on a specific position.
```php
Widget::group('sidebar')->position(42)->addWidget('files');
Widget::group('sidebar')->position(42)->addAsyncWidget('files');
Widget::group('sidebar')->removeByPosition(42); // Widget group is empty now
```

4) Remove all widgets at once.
```php
Widget::group('sidebar')->addWidget('files');
Widget::group('sidebar')->addAsyncWidget('files');
Widget::group('sidebar')->removeAll(); // Widget group is empty now
```

### Checking the state of a group

`Widget::group('sidebar')->isEmpty(); // bool`

`Widget::group('sidebar')->any(); // bool`

`Widget::group('sidebar')->count(); // int`

## Namespaces for third party packages (extra)

In some cases, it may be useful to deliver widgets with your own packages. For example, if your package allows 
you to manage news, it would be convenient to have immediately configurable widgets, ready for display, directly 
delivered with your package.

To avoid having to use the fqcn each time, you can set a widget namespace into your package provider. This way the 
widgets from your package can be more easily identified, and especially the syntax will be shorter.

To do that, all you have to do is to register the namespace in your package service provider :

```php
public function boot() 
{
    app('arrilot.widget-namespaces')->registerNamespace('my-package-name', '\VendorName\PackageName\Path\To\Widgets');
}
```

After that you can use the namespace in your views :

```php
@widget('my-package-name::foo.bar')

// is equivalent to
@widget('\VendorName\PackageName\Path\To\Widgets\Foo\Bar')
```
 