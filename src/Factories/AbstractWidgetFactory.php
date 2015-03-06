<?php namespace Arrilot\Widgets\Factories;

abstract class AbstractWidgetFactory {

    protected $config;

    /**
     * Constructor
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
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
}