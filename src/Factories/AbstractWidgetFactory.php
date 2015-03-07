<?php namespace Arrilot\Widgets\Factories;

use Arrilot\Widgets\AbstractWidget;
use Arrilot\Widgets\InvalidWidgetClassException;

abstract class AbstractWidgetFactory {

    /**
     * Factory config.
     *
     * @var array
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Determine widget namespace.
     *
     * @param $widgetName
     * @return mixed
     */
    protected function determineNamespace($widgetName)
    {
        foreach ([$widgetName, strtolower($widgetName)] as $name)
        {
            if (array_key_exists($name, $this->config['customNamespaces']))
            {
                return $this->config['customNamespaces'][$name];
            }
        }

        return $this->config['defaultNamespace'];
    }

    /**
     * Instantiate the widget object.
     *
     * @param $widgetName
     * @param $params
     * @return mixed
     * @throws InvalidWidgetClassException
     */
    protected function instantiateWidget($widgetName, $params)
    {
        $config = isset($params[0]) ? $params[0] : [];

        $widgetName = studly_case($widgetName);

        $namespace   = $this->determineNamespace($widgetName);
        $widgetClass = $namespace . '\\' . $widgetName;

        $widget = new $widgetClass($config);
        if ($widget instanceof AbstractWidget === false)
        {
            throw new InvalidWidgetClassException;
        }

        return $widget;
    }
}