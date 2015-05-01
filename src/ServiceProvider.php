<?php

namespace Arrilot\Widgets;

use Arrilot\Widgets\Console\WidgetMakeCommand;
use Arrilot\Widgets\Factories\AsyncWidgetFactory;
use Arrilot\Widgets\Factories\WidgetFactory;
use Arrilot\Widgets\Misc\Wrapper;
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

        $config = [
            'defaultNamespace' => config('laravel-widgets.default_namespace') ?: $this->getAppNamespace().'Widgets',
            'customNamespaces' => config('laravel-widgets.custom_namespaces_for_specific_widgets', []),
        ];

        $this->app->bind('arrilot.widget', function () use ($config) {
            return new WidgetFactory($config, new Wrapper());
        });

        $this->app->bind('arrilot.async-widget', function () use ($config) {
            return new AsyncWidgetFactory($config, new Wrapper());
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
            $router->post('async-widget', 'WidgetController@showAsyncWidget');
        });

        $this->registerBladeExtensions();
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
     * Register blade extensions.
     */
    protected function registerBladeExtensions()
    {
        Blade::extend(function ($view) {
            $pattern = $this->createMatcher('widget');

            return preg_replace($pattern, '$1<?php echo Widget::run$2; ?>', $view);
        });

        Blade::extend(function ($view) {
            $pattern = $this->createMatcher('async-widget');

            return preg_replace($pattern, '$1<?php echo AsyncWidget::run$2; ?>', $view);
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
    public function createMatcher($function)
    {
        return '/(?<!\w)(\s*)@'.$function.'(\s*\(.*\))/';
    }
}
