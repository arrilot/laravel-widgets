[![Build Status](https://travis-ci.org/Arrilot/laravel-widgets.svg?branch=master)](https://travis-ci.org/Arrilot/laravel-widgets)

#Easy widgets for Laravel
=====================

*This packages provides a basic widget functionality to boost your views. Really fast and convinient workflow at the expense of limitted flexibility*

## Installation

In the `require` key of `composer.json` file add `arrilot/laravel-widgets": "*`:

```
...
"require": {
	"laravel/framework": "4.2.*",
	"lavary/laravel-menu": "dev-master"
  }  
```
  
Run the composer update command:

```bash
composer update
```

Register a service provider in `app/config/app.php`.

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

Add a facade here too.

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

First of all we can create a Widget using artisan generator provided by the package.
```bash
php artisan make:widget RecentNews
```

It creates the folowing widget skeleton:
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

As soon as domain logic is implemented inside the run() method, it can be included to a view like that:
```php
{{ Widget::recentNews() }}
```
Make sure the widget class can be autoloaded by composer.

## Configuration

### Namespaces configuration
By default package tries to find your widget in
```php
App\Widgets
```
namespace.

You can override it by changing 'default namespace' property in the package config (Do not forget to publish package config before that).

Althought using default namespace is very convinient, you can also set custom namespaces for specific widgets:
```php
    'custom_namespaces_for_specific_widgets' => [
        'widgetName' => 'Widget\Namespace\Here'
        ....
    ]
```

### Widget configuratuion

Let's carry on with the "recent news" example.

Imagine that we usually need to show 5 news, but in some views we need to show 10.
This can be easily achieved like that:

```php
class RecentNews extends AbstractWidget {
    ...
    protected count = 5;
    ...
}

...
{{ Widget::recentNews() }}
...
{{ Widget::recentNews(['count' => 10]) }}
```
Notice that you don't need to map ['count' => 10] and class property in constructor. It's done automatically.
