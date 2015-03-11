<?php namespace Arrilot\Widgets\Factories;

use Illuminate\Support\Facades\App;

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
        return $this->wrapper->appCall([$widget, 'run'], $this->widgetParams);
        //return call_user_func([$widget, 'run'], $this->widgetParams);
    }

}