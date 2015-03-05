<?php namespace Arrilot\Widgets;

class AsyncWidgetFactory extends AbstractWidgetFactory
{

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
        AbstractWidget::incrementId();

        $config = isset($params[0]) ? $params[0] : [];

        $query = http_build_query([
            'widget' => [
                'name'   => $widgetName,
                'config' => $config
            ]
        ]);
        $resourceLink = "/arrilot/async-widgets?".$query;

        dd($resourceLink);

        return "1";
    }

}