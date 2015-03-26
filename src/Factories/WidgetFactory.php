<?php namespace Arrilot\Widgets\Factories;

use Illuminate\Support\Facades\App;

class WidgetFactory extends AbstractWidgetFactory {

    /**
     * Magic method that catches all widget calls.
     *
     * @param $widgetName
     * @param array $params
     *
     * @return mixed
     */
    public function __call($widgetName, array $params = [])
    {
        return $this->runWidget($widgetName, $params);
    }
    
    /**
     * Run widget without magic method.
     *
     * @return mixed
     */
    public function run()
    {
        $params = func_get_args();
        $widgetName = array_shift($params);
        $widgetName = $this->parseFullWidgetNameFromString($widgetName);

        return $this->runWidget($widgetName, $params);
    }

    /**
     * Instantiate widget object and resolve it's run method out of IoC container.
     *
     * @param $widgetName
     * @param array $params
     *
     * @return mixed
     */
    protected function runWidget($widgetName, array $params)
    {
        $widget = $this->instantiateWidget($widgetName, $params);

        return $this->wrapper->appCall([$widget, 'run'], $this->widgetParams);
    }

}