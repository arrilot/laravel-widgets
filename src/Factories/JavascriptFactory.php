<?php

namespace Arrilot\Widgets\Factories;

use Arrilot\Widgets\WidgetId;

class JavascriptFactory
{
    /**
     * Widget factory object.
     *
     * @var AbstractWidgetFactory
     */
    protected $widgetFactory;

    /**
     * @param $widgetFactory
     */
    public function __construct($widgetFactory)
    {
        $this->widgetFactory = $widgetFactory;
    }

    /**
     * Ajax link where widget can grab content.
     *
     * @var string
     */
    protected $ajaxLink = '/arrilot/load-widget';

    /**
     * Construct javascript to load the widget.
     */
    public function getLoader()
    {
        return "<script type=\"text/javascript\">$('#{$this->getContainerId()}').load('".$this->ajaxLink."', {$this->produceDataObject()})</script>";
    }

    /**
     * Construct javascript to reload the widget.
     *
     * @param float|int $timeout
     *
     * @return string
     */
    public function getReloader($timeout)
    {
        $timeout = $timeout * 1000;

        return "<script type=\"text/javascript\">setTimeout( function() { $('#{$this->getContainerId()}').load('".$this->ajaxLink."', {$this->produceDataObject()}) }, $timeout)</script>";
    }

    /**
     * Produce javascript data object for ajax call.
     *
     * @return string
     */
    protected function produceDataObject()
    {
        return json_encode([
            'id'     => $this->getWidgetId(),
            'name'   => $this->widgetFactory->widgetName,
            'params' => serialize($this->widgetFactory->widgetFullParams),
            '_token' => $this->widgetFactory->wrapper->csrf_token(),
            'skip_widget_container' => 1, //to avoid container duplication
        ]);
    }

    /**
     * Get the current widget id.
     *
     * @return string
     */
    public function getWidgetId()
    {
        return WidgetId::get();
    }

    /**
     * Get the current widget container id.
     *
     * @return string
     */
    public function getContainerId()
    {
        return 'arrilot-widget-container-'.$this->getWidgetId();
    }
}
