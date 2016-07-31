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
     * Determine what to use - jquery or native js.
     *
     * @return bool
     */
    protected function useJquery()
    {
        return $this->widgetFactory->app->config('laravel-widgets.use_jquery_for_ajax_calls', false);
    }

    /**
     * Construct ajax call for loaders.
     *
     * @return string
     */
    protected function constructAjaxCall()
    {
        $queryParams = [
            'id'     => WidgetId::get(),
            'name'   => $this->widgetFactory->widgetName,
            'params' => $this->widgetFactory->encryptWidgetParams($this->widgetFactory->widgetFullParams),
        ];

        $url = $this->ajaxLink.'?'.http_build_query($queryParams);

        return $this->useJquery()
            ? $this->constructJqueryAjaxCall($url)
            : $this->constructNativeJsAjaxCall($url);
    }

    /**
     * Construct ajax call with jquery.
     *
     * @param string $url
     *
     * @return string
     */
    protected function constructJqueryAjaxCall($url)
    {
        $id = WidgetId::get();

        return
            "var widgetTimer{$id} = setInterval(function() {".
                'if (window.$) {'.
                    "$('#{$this->getContainerId()}').load('{$url}');".
                    "clearInterval(widgetTimer{$id});".
                '}'.
            '}, 100);';
    }

    /**
     * Construct ajax call without jquery.
     *
     * @param string $url
     *
     * @return string
     */
    protected function constructNativeJsAjaxCall($url)
    {
        return
            'setTimeout(function() {'.
                'var xhr = new XMLHttpRequest();'.
                'xhr.open("GET", "'.$url.'", true);'.
                'xhr.onreadystatechange = function() {'.
                    'if(xhr.readyState == 4 && xhr.status == 200) {'.
                        'var container = document.getElementById("'.$this->getContainerId().'");'.
                        'container.innerHTML = xhr.responseText;'.
                        'var scripts = container.getElementsByTagName("script");'.
                        'for(var i=0; i < scripts.length; i++) {'.
                            'if (window.execScript) {'.
                                'window.execScript(scripts[i].text);'.
                            '} else {'.
                                'window["eval"].call(window, scripts[i].text);'.
                            '}'.
                        '}'.
                    '}'.
                '};'.
                'xhr.send();'.
            '}, 0);';
    }
}
