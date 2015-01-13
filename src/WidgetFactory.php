<?php namespace Arrilot\Widgets;

class WidgetFactory {

	protected $defaultNamespace;
	protected $customNamespaces;


	/**
	 * Constructor
	 * @param $defaultNamespace
	 * @param $customNamespaces
	 */
	public function __construct($defaultNamespace, $customNamespaces)
	{
		$this->defaultNamespace = $defaultNamespace;
		$this->customNamespaces = $customNamespaces;
	}


	/**
	 * Magic method that catches all widget calls
	 *
	 * @param $widgetName
	 * @param array $params
	 */
	public function __call($widgetName, $params = [])
	{
		$config = isset($params[0]) ? $params[0] : [];

		$widgetName       = studly_case($widgetName);

		$namespace        = $this->determineNamespace($widgetName);
		$widgetClass      = $namespace . '\\' . $widgetName;

		$widget = new $widgetClass($config);
		return $widget->run();
	}


	/**
	 * @param $widgetName
	 * @return mixed
	 */
	public function determineNamespace($widgetName)
	{

		if (array_key_exists($widgetName, $this->customNamespaces))
		{
			return $this->customNamespaces[$widgetName];
		}

		$widgetName = strtolower($widgetName);

		if (array_key_exists($widgetName, $this->customNamespaces))
		{
			return $this->customNamespaces[$widgetName];
		}

		return $this->defaultNamespace;
	}
}