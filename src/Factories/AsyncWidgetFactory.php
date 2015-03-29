<?php namespace Arrilot\Widgets\Factories;

use Arrilot\Widgets\AbstractWidget;

class AsyncWidgetFactory extends AbstractWidgetFactory {

    /**
     * Ajax link where async widget can grab content
     *
     * @var string
     */
    protected $ajaxLink = "/arrilot/async-widget";

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

        AbstractWidget::$incrementingId++;

        $widget = $this->instantiateWidget($params);

        $containerId = 'async-widget-container-' . AbstractWidget::$incrementingId;
        $container   = "<span id='{$containerId}'>" . call_user_func([$widget, 'placeholder']) . "</span>";
        $loader      = "<script>$.post('" . $this->ajaxLink . "', " . $this->produceJavascriptData() . ", function(data) { $('#{$containerId}').replaceWith(data); })</script>";

        return $container . $loader;
    }

    /**
     * Produce javascript data object for ajax call.
     *
     * @return string
     */
    protected function produceJavascriptData()
    {
        return json_encode([
            'name'   => $this->widgetName,
            'params' => serialize($this->widgetFullParams),
            '_token' => $this->wrapper->csrf_token()
        ]);
    }

}