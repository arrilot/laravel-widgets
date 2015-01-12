<?php namespace Arrilot\Widgets;

use Config;

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
			return new WidgetFactory();
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
