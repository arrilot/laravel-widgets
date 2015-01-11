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

	}


	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['config']->set('laravel-widgets', require __DIR__ .'/../config/config.php');


		$this->app->bind('arrilot_widget', function(){
			$defaultNamespace = Config::get('laravel-widgets.default_namespace');
			$customNamespaces = Config::get('laravel-widgets.custom_namespaces_for_specific_widgets', []);
			return new WidgetFactory($defaultNamespace, $customNamespaces);
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
