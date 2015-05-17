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
     * Ajax link where widget can grab content.
     *
     * @var string
     */
    protected $ajaxLink = '/arrilot/load-widget';

    /**
     * @param $widgetFactory
     */
    public function __construct(AbstractWidgetFactory $widgetFactory)
    {
        $this->widgetFactory = $widgetFactory;
    }

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
            'id'     => WidgetId::get(),
            'name'   => $this->widgetFactory->widgetName,
            'params' => serialize($this->widgetFactory->widgetFullParams),
            '_token' => $this->widgetFactory->wrapper->csrf_token()
        ]);
    }

    /**
     * Get the current widget container id.
     *
     * @return string
     */
    public function getContainerId()
    {
        return 'arrilot-widget-container-'.WidgetId::get();
    }
}
