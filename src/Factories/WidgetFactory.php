<?php namespace Arrilot\Widgets\Factories;

class WidgetFactory extends AbstractWidgetFactory {

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

}