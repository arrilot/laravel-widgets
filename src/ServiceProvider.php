<?php

namespace Arrilot\Widgets;

use Arrilot\Widgets\Console\WidgetMakeCommand;
use Arrilot\Widgets\Factories\AsyncWidgetFactory;
use Arrilot\Widgets\Factories\WidgetFactory;
use Arrilot\Widgets\Misc\LaravelApplicationWrapper;
use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Support\Facades\Blade;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    use AppNamespaceDetectorTrait;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/config.php', 'laravel-widgets'
        );

        $this->app->bind('arrilot.widget', function () {
            return new WidgetFactory(new LaravelApplicationWrapper());
        });

        $this->app->bind('arrilot.async-widget', function () {
            return new AsyncWidgetFactory(new LaravelApplicationWrapper());
        });

        $this->app->singleton('command.widget.make', function ($app) {
            return new WidgetMakeCommand($app['files']);
        });

        $this->commands('command.widget.make');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('laravel-widgets.php'),
        ]);

        $routeConfig = [
            'namespace' => 'Arrilot\Widgets\Controllers',
            'prefix'    => 'arrilot',
        ];

        $this->app['router']->group($routeConfig, function ($router) {
            $router->post('load-widget', 'WidgetController@showWidget');
        });

        $this->registerBladeDirectives();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['arrilot.widget', 'arrilot.async-widget'];
    }

    /**
     * Register blade directives.
     */
    protected function registerBladeDirectives()
    {
        Blade::extend(function ($view) {
            $pattern = $this->createMatcher('widget');

            return preg_replace($pattern, '$1<?php echo app("arrilot.widget")->run$2; ?>', $view);
        });

        Blade::extend(function ($view) {
            $pattern = $this->createMatcher('async-widget');

            return preg_replace($pattern, '$1<?php echo app("arrilot.async-widget")->run$2; ?>', $view);
        });

        Blade::extend(function ($view) {
            $pattern = $this->createMatcher('asyncWidget');

            return preg_replace($pattern, '$1<?php echo app("arrilot.async-widget")->run$2; ?>', $view);
        });

        Blade::extend(function ($view) {
            $pattern = $this->createMatcher('widgetGroup');

            return preg_replace($pattern, '$1<?php echo Widget::group$2->display(); ?>', $view);
        });
    }

    /**
     * Substitution for $compiler->createMatcher().
     *
     * Get the regular expression for a generic Blade function.
     *
     * @param string $function
     *
     * @return string
     */
    protected function createMatcher($function)
    {
        return '/(?<!\w)(\s*)@'.$function.'(\s*\(.*\))/';
    }
}
