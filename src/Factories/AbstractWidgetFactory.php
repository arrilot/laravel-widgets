<?php

namespace Arrilot\Widgets\Factories;

use Arrilot\Widgets\AbstractWidget;
use Arrilot\Widgets\Misc\InvalidWidgetClassException;
use Arrilot\Widgets\WidgetId;

abstract class AbstractWidgetFactory
{
    /**
     * Factory config.
     *
     * @var array
     */
    protected $config;

    /**
     * Widget object to work with.
     *
     * @var AbstractWidget
     */
    protected $widget;

    /**
     * Widget configuration array.
     *
     * @var array
     */
    protected $widgetConfig;

    /**
     * The name of the widget being called.
     *
     * @var string
     */
    public $widgetName;

    /**
     * Array of widget parameters excluding the first one (config).
     *
     * @var array
     */
    public $widgetParams;

    /**
     * Array of widget parameters including the first one (config).
     *
     * @var array
     */
    public $widgetFullParams;

    /**
     * Laravel application wrapper for better testability.
     *
     * @var \Arrilot\Widgets\Misc\Wrapper;
     */
    public $wrapper;

    /**
     * Another factory that produces some javascript.
     *
     * @var JavascriptFactory
     */
    protected $javascriptFactory;

    /**
     * @param $config
     * @param $wrapper
     */
    public function __construct($config, $wrapper)
    {
        $this->config = $config;
        $this->wrapper = $wrapper;
        $this->javascriptFactory = new JavascriptFactory($this);
    }

    /**
     * Magic method that catches all widget calls.
     *
     * @param string $widgetName
     * @param array  $params
     *
     * @return mixed
     */
    public function __call($widgetName, array $params = [])
    {
        array_unshift($params, $widgetName);

        return call_user_func_array([$this, 'run'], $params);
    }

    /**
     * Determine widget namespace.
     *
     * @return mixed
     */
    protected function determineWidgetNamespace()
    {
        // search in custom namespaces first
        foreach ([$this->widgetName, strtolower($this->widgetName)] as $name) {
            if (array_key_exists($name, $this->config['customNamespaces'])) {
                return $this->config['customNamespaces'][$name];
            }
        }

        return $this->config['defaultNamespace'];
    }

    /**
     * Set class properties and instantiate a widget object.
     *
     * @param $params
     *
     * @throws InvalidWidgetClassException
     */
    protected function instantiateWidget(array $params = [])
    {
        WidgetId::increment();

        $this->widgetName = $this->parseFullWidgetNameFromString(array_shift($params));
        $this->widgetFullParams = $params;
        $this->widgetConfig = array_shift($params);
        $this->widgetParams = $params;

        $widgetClass = $this->determineWidgetNamespace().'\\'.$this->widgetName;

        $widget = new $widgetClass($this->widgetConfig);
        if ($widget instanceof AbstractWidget === false) {
            throw new InvalidWidgetClassException();
        }

        $this->widget = $widget;
    }

    /**
     * Convert stuff like 'profile.feedWidget' to 'Profile\FeedWidget'.
     *
     * @param $widgetName
     *
     * @return string
     */
    protected function parseFullWidgetNameFromString($widgetName)
    {
        return studly_case(str_replace('.', '\\', $widgetName));
    }

    /**
     * Wrap the given content in a span if it's not an ajax call.
     *
     * @param $content
     * @return string
     */
    protected function wrapContentInContainer($content)
    {
        return isset($_POST['skip_widget_container']) ?
            $content : '<span id="'.$this->javascriptFactory->getContainerId().'" class="arrilot-widget-container">'.$content.'</span>';
    }
}
