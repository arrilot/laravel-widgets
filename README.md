[![Build Status](https://travis-ci.org/Arrilot/laravel-widgets.svg?branch=master)](https://travis-ci.org/Arrilot/laravel-widgets)

#Simple widgets for Laravel

*This packages provides a basic widget functionality to boost your views. Really fast and convinient workflow at the expense of limitted flexibility*

## Installation

First, use some composer awesomeness:

```bash
composer require arrilot/laravel-widgets
```

Then, register a service provider in your `app.php` config file

```php
<?php

'providers' => array(

    'Illuminate\Foundation\Providers\ArtisanServiceProvider',
    'Illuminate\Auth\AuthServiceProvider',
    ...
    'Arrilot\Widgets\ServiceProvider',

),
?>
```

Finally, add a facade here too.

```php
<?php

'aliases' => array(

    'App'        => 'Illuminate\Support\Facades\App',
    'Artisan'    => 'Illuminate\Support\Facades\Artisan',
    ...
    'Widget'     => 'Arrilot\Widgets\Facade',

),
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
{{ Widget::recentNews() }}
```
Make sure the widget class can be autoloaded by composer.

## Configuration

### Namespaces configuration
By default package tries to find your widget in the ```App\Widgets``` namespace.

You can override this by changing `default_namespace` property in the package config.

Althought using the default namespace is very convinient and keeps you from doing unnecessary actions, you can also set custom namespaces for specific widgets:
```php
    'custom_namespaces_for_specific_widgets' => [
        'widgetName' => 'Widget\Namespace\Here'
        ....
    ]
```

Note: do not forget to publish package config before making these changes.
```bash
php artisan config:publish arrilot/laravel-widgets
```

### Widget configuration

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
{{ Widget::recentNews() }}
...
{{ Widget::recentNews(['count' => 10]) }}
```
Notice that you don't need to map `['count' => 10]` and class property in constructor. It's done automatically behind the scene
