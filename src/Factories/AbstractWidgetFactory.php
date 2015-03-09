<?php namespace Arrilot\Widgets\Factories;

use Arrilot\Widgets\AbstractWidget;
use Arrilot\Widgets\InvalidWidgetClassException;

abstract class AbstractWidgetFactory {

    /**
     * Factory config.
     *
     * @var array
     */
    protected $factoryConfig;

    protected $widgetConfig;

    protected $widgetName;

    protected $wrapper;

    protected $widgetParams;

    protected $widgetFullParams;


    /**
     * Constructor.
     *
     * @param $factoryConfig
     * @param $wrapper
     */
    public function __construct($factoryConfig, $wrapper)
    {
        $this->factoryConfig = $factoryConfig;
        $this->wrapper = $wrapper;
    }

    /**
     * Determine widget namespace.
     *
     * @return mixed
     */
    protected function determineNamespace()
    {
        foreach ([$this->widgetName, strtolower($this->widgetName)] as $name)
        {
            if (array_key_exists($name, $this->factoryConfig['customNamespaces']))
            {
                return $this->factoryConfig['customNamespaces'][$name];
            }
        }

        return $this->factoryConfig['defaultNamespace'];
    }

    /**
     * Set class properties and instantiate widget object.
     *
     * @param $widgetName
     * @param $params
     * @return mixed
     * @throws InvalidWidgetClassException
     */
    protected function instantiateWidget($widgetName, array $params = [])
    {
        $this->widgetName       = studly_case($widgetName);
        $this->widgetFullParams = $params;
        $this->widgetConfig     = array_shift($params);
        $this->widgetParams     = $params;

        $widgetClass = $this->determineNamespace() . '\\' . $this->widgetName;

        $widget = new $widgetClass($this->widgetConfig);
        if ($widget instanceof AbstractWidget === false)
        {
            throw new InvalidWidgetClassException;
        }

        return $widget;
    }
}