<?php namespace Arrilot\Widgets;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

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
    }

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
            'defaultNamespace' => config('laravel-widgets.default_namespace'),
            'customNamespaces' => config('laravel-widgets.custom_namespaces_for_specific_widgets', [])
        ];

        $this->app->bind('arrilot.widget', function() use ($config)
        {
            return new WidgetFactory($config);
        });

        $this->app->bind('arrilot.async-widget', function() use ($config)
        {
            return new AsyncWidgetFactory($config);
        });

        $this->app->singleton('command.widget.make', function($app)
        {
            return new WidgetMakeCommand($app['files']);
        });

        $this->commands('command.widget.make');
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

}