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
     * Construct javascript code to load the widget.
     *
     * @return string
     */
    public function getLoader()
    {
        return
            '<script type="text/javascript">'.
                $this->constructAjaxCall().
            '</script>';
    }

    /**
     * Construct javascript code to reload the widget.
     *
     * @param float|int $timeout
     *
     * @return string
     */
    public function getReloader($timeout)
    {
        $timeout = $timeout * 1000;

        return
            '<script type="text/javascript">'.
                'setTimeout( function() {'.
                    $this->constructAjaxCall().
                '}, '.$timeout.')'.
            '</script>';
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

    /**
     * Produce javascript data object for ajax call.
     *
     * @return string
     */
    protected function produceJavascriptData()
    {
        $id = WidgetId::get();
        $name = $this->widgetFactory->widgetName;
        $params = serialize($this->widgetFactory->widgetFullParams);
        $token = $this->widgetFactory->app->csrf_token();

        if ($this->useJquery()) {
            return json_encode([
                'id'     => $id,
                'name'   => $name,
                'params' => $params,
                '_token' => $token,
            ]);
        }

        return
            "'id=' + encodeURIComponent('{$id}')".
            "'&name=' + encodeURIComponent('{$name}')".
            "'&params=' + encodeURIComponent('{$params}')".
            "'&_token=' + encodeURIComponent('{$token}')";
    }

    /**
     * Determine what to use - jquery or native js.
     *
     * @return bool
     */
    protected function useJquery()
    {
        return !$this->widgetFactory->app->config('laravel-widgets.disable_jquery', false);
    }

    /**
     * Construct ajax call for loaders.
     *
     * @return string
     */
    protected function constructAjaxCall()
    {
        if ($this->useJquery()) {
            $id = WidgetId::get();

            return
                "var widgetTimer{$id} = setInterval(function() {".
                    'if (window.$) {'.
                        "$('#{$this->getContainerId()}').load('".$this->ajaxLink."', {$this->produceJavascriptData()});".
                        "clearInterval(widgetTimer{$id});".
                    '}'.
                '}, 100);';
        }

        return
            'var xhr = new XMLHttpRequest();'.
            'var params = '.$this->produceJavascriptData().';'.
            'var container = document.getElementById("'.$this->getContainerId().'");'.
            'xhr.open("POST", "'.$this->ajaxLink.'", true);'.
            'xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");'.
            'xhr.send(params);'.
            'xhr.onreadystatechange = function() {'.
                'if(xhr.readyState == 4 && xhr.status == 200) {'.
                    'var data = xhr.responseText;'.
                    'container.innerHTML = data;'.
                    'var scripts = container.getElementsByTagName("script");'.
                    'for(var i=0; i < scripts.length; i++) {'.
                        'if (window.execScript) {'.
                            'window.execScript(scripts[i].text);'.
                        '} else {'.
                            'window["eval"].call(window, scripts[i].text);'.
                        '}'.
                    '}'.
                '}'.
            '}';
    }
}
