<?php namespace Arrilot\Widgets;

use Illuminate\Support\Facades\Config;

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
		$this->app['config']->package('arrilot/laravel-widgets', __DIR__ . '/config');
	}


	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('arrilot_widget', function(){

			$config = [
				'defaultNamespace' => Config::get('laravel-widgets::default_namespace'),
				'customNamespaces' => Config::get('laravel-widgets::custom_namespaces_for_specific_widgets', [])
			];
			return new WidgetFactory($config);
		});


		$this->app['make.widget'] = $this->app->share(function($app)
		{
			$generator = $this->app->make('Way\Generators\Generator');

			return new MakeWidgetCommand($generator);
		});
		$this->commands('make.widget');
	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['arrilot_widget'];
	}

}
