<?php namespace Arrilot\Widgets\Factories;

use Arrilot\Widgets\InvalidWidgetClassException;

class WidgetFactory extends AbstractWidgetFactory {

    /**
     * Magic method that catches all widget calls.
     *
     * @param $widgetName
     * @param array $params
     * @return mixed
     */
    public function __call($widgetName, $params = [])
    {
        $widget = $this->instantiateWidget($widgetName, $params);

        return $widget->run();
    }


}