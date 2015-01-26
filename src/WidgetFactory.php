<?php namespace Arrilot\Widgets;

class WidgetFactory {

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
     * Magic method that catches all widget calls
     *
     * @param $widgetName
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function __call($widgetName, $params = [])
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

        return $widget->run();
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