<?php

namespace Arrilot\Widgets\Factories;

use Arrilot\Widgets\WidgetGroup;

class WidgetFactory extends AbstractWidgetFactory
{
    protected $groups;

    /**
     * Run widget without magic method.
     *
     * @return mixed
     */
    public function run()
    {
        $params = func_get_args();
        $widgetName = array_shift($params);
        $this->parseFullWidgetNameFromString($widgetName);

        $widget = $this->instantiateWidget($params);

        return $this->wrapper->appCall([$widget, 'run'], $this->widgetParams);
    }

    /**
     * Get the widget group object.
     *
     * @param $name
     *
     * @return mixed
     */
    public function group($name)
    {
        if ($this->groups[$name]) {
            return $this->groups[$name];
        }

        $this->groups[$name] = new WidgetGroup($name);

        return $this->groups[$name];
    }
}
