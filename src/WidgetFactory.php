<?php namespace Arrilot\Widget;

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

		$widgetName  = studly_case($widgetName);
		$namespace   = $this->determineNamespace($widgetName);
		$widgetClass = $namespace.'\\'.$widgetName;

		$widget = new $widgetClass($config);
		$widget->run();

	}


	/**
	 * @param $widgetName
	 * @return mixed
	 */
	protected function determineNamespace($widgetName)
	{
		$customNamespaces = Config::get('widget::custom_namespaces_for_specific_widgets', []);

		if (array_key_exists($widgetName, $customNamespaces))
		{
			return $customNamespaces[$widgetName];
		}

		$widgetName = strtolower($widgetName);
		if (array_key_exists($widgetName, $customNamespaces))
		{
			return $customNamespaces[$widgetName];
		}

		return Config::get('widget::base_namespace');
	}
}