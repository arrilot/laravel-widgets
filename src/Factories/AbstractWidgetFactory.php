<?php namespace Arrilot\Widgets\Factories;

use Arrilot\Widgets\AbstractWidget;
use Arrilot\Widgets\InvalidWidgetClassException;
use Arrilot\Widgets\WidgetGroup;

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

	protected $groups;


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
     * Magic method that catches all widget calls.
     *
     * @param string $widgetName
     * @param array $params
     * @return mixed
     */
    public function __call($widgetName, array $params = [])
    {
        array_unshift($params, $widgetName);

        return call_user_func_array([$this, 'run'], $params);
    }


	public function group($name)
	{
		if ($this->groups[$name])
		{
			return $this->groups[$name];
		}

		$this->groups[$name] = new WidgetGroup($name);

		return $this->groups[$name];
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
     * @param $params
     * @return mixed
     * @throws InvalidWidgetClassException
     */
    protected function instantiateWidget(array $params = [])
    {
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

    /**
     * Converts stuff like 'profile.feedWidget' to 'Profile\FeedWidget'
     *
     * @param $widgetName
     *
     * @return string
     */
    protected function parseFullWidgetNameFromString($widgetName)
    {
        $this->widgetName = studly_case(str_replace('.', '\\', $widgetName));
    }
}