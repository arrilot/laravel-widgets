<?php namespace Arrilot\Widgets;

use Config;

class WidgetFactory
{

	/**
	 * Magic method that catches all widget calls
	 *
	 * @param $widgetName
	 * @param array $params
	 */
	public function __call($widgetName, $params = [])
	{
		$config = $params[0] ?: [];

		$widgetName       = studly_case($widgetName);
		$customNamespaces = Config::get('laravel-widgets::custom_namespaces_for_specific_widgets', []);
		$defaultNamespace = Config::get('laravel-widgets::default_namespace');

		$namespace        = $this->determineNamespace($widgetName, $customNamespaces, $defaultNamespace);

		$widgetClass      = $namespace . '\\' . $widgetName;

		$widget = new $widgetClass($config);
		$widget->run();

	}


	/**
	 * @param $widgetName
	 * @param $customNamespaces
	 * @param $defaultNamespace
	 * @return mixed
	 */
	public function determineNamespace($widgetName, $customNamespaces, $defaultNamespace)
	{

		if (array_key_exists($widgetName, $customNamespaces))
		{
			return $customNamespaces[$widgetName];
		}

		$widgetName = strtolower($widgetName);
		if (array_key_exists($widgetName, $customNamespaces))
		{
			return $customNamespaces[$widgetName];
		}

		return $defaultNamespace;
	}
}