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

        $ajaxLink = $this->getAjaxLink($widgetName, $params);

        $divId  = 'async-widget-'.AbstractWidget::getId();
        $div    = "<div id='{$divId}'></div>";
        $loader = "<script>$('#{$divId}').load('{$ajaxLink}')</script>";

        return $div.$loader;
    }


    /**
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