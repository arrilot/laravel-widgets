<?php namespace Arrilot\Widgets\Factories;

class WidgetFactory extends AbstractWidgetFactory {

    /**
     * Magic method that catches all widget calls.
     *
     * @param $widgetName
     * @param array $params
     * @return mixed
     */
    public function __call($widgetName, array $params = [])
    {
        $widget = $this->instantiateWidget($widgetName, $params);

        return call_user_func_array([$widget, 'run'], $this->widgetParams);
    }

}