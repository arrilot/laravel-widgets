<?php namespace Arrilot\Widgets;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

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



        $this->app->bind('arrilot.widget', function()
        {
            return new WidgetFactory();
        });

        $this->app->bind('arrilot.async-widget', function()
        {
            return new AsyncWidgetFactory();
        });

        $this->app->singleton('command.widget.make', function($app)
        {
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
            'prefix' => 'arrilot',
        ];

        $this->app['router']->group($routeConfig, function($router)
        {
            $router->get('async-widget','WidgetController@showAsyncWidget');
        });
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