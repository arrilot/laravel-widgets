<?php namespace Arrilot\Widgets;

use Symfony\Component\Console\Input\InputArgument;
use Way\Generators\Commands\GeneratorCommand;

class MakeWidgetCommand extends GeneratorCommand {

	protected $name = 'make:widget';
	protected $description = 'Create a new widget (arrilot/laravel-widgets)';


	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['widgetName', InputArgument::REQUIRED, 'The name of the desired widget.']
		];
	}


	/**
	 * The path where the file will be created
	 *
	 * @return mixed
	 */
	protected function getFileGenerationPath()
	{
		$widgets_dir = app_path('Widgets');
		if (!file_exists($widgets_dir))
		{
			mkdir($widgets_dir, 0777, true);
		}

		return $widgets_dir . '/' . studly_case($this->argument('widgetName')) . '.php';
	}


	/**
	 * Template which is used for generation
	 *
	 * @return string
	 */
	protected function getTemplatePath()
	{
		return str_replace( base_path() . "/", "", __DIR__) . "/templates/widget.txt";
	}


	/**
	 * Data which is passed to the template
	 * @return array
	 */
	protected function getTemplateData()
	{
		return [
			'NAME' => studly_case($this->argument('widgetName'))
		];
	}

}
