<?php namespace Arrilot\Widget;

use Config;

class WidgetFactory
{
	/**
	 * Magic method that catches all widget calls
	 *
	 * @param $widgetName
	 * @param array $config
	 */
	public function __call($widgetName, array $config = [])
	{
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