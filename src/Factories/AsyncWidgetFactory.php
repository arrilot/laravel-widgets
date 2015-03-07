<?php namespace Arrilot\Widgets\Factories;

use Arrilot\Widgets\AbstractWidget;

class AsyncWidgetFactory extends AbstractWidgetFactory {

    /**
     * Magic method that catches all widget calls.
     *
     * @param $widgetName
     * @param array $params
     * @return mixed
     */
    public function __call($widgetName, $params = [])
    {
        AbstractWidget::$incrementingId++;

        $ajaxLink = $this->getAjaxLink($widgetName, $params);
        $widget = $this->instantiateWidget($widgetName, $params);

        $divId  = 'async-widget-container-'.AbstractWidget::$incrementingId;
        $div    = "<div id='{$divId}'>{$widget->placeholder()}</div>";
        $loader = "<script>$('#{$divId}').load('{$ajaxLink}')</script>";

        return $div.$loader;
    }

    /**
     * Constructs the ajax link for the sync widget content.
     *
     * @param $widgetName
     * @param $params
     * @return string
     */
    protected function getAjaxLink($widgetName, $params)
    {
        $config = isset($params[0]) ? $params[0] : [];

        $query = http_build_query([
            'widget' => [
                'name'   => $widgetName,
                'config' => $config
            ]
        ]);

        return "/arrilot/async-widget?" . $query;
    }

}