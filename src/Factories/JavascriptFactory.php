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
        return !$this->widgetFactory->app->config('laravel-widgets.disable_jquery', false);
    }

    /**
     * Construct ajax call for loaders.
     *
     * @return string
     */
    protected function constructAjaxCall()
    {
        $data = [
            'id'     => WidgetId::get(),
            'name'   => $this->widgetFactory->widgetName,
            'params' => serialize($this->widgetFactory->widgetFullParams),
            '_token' => $this->widgetFactory->app->csrf_token(),
        ];

        return $this->useJquery()
            ? $this->constructJqueryAjaxCall($data)
            : $this->constructNativeJsAjaxCall($data);
    }

    /**
     * Construct ajax call with jquery.
     *
     * @param array $data
     *
     * @return string
     */
    protected function constructJqueryAjaxCall(array $data)
    {
        $id = WidgetId::get();

        $jsData = json_encode($data);

        return
            "var widgetTimer{$id} = setInterval(function() {".
                'if (window.$) {'.
                    "$('#{$this->getContainerId()}').load('".$this->ajaxLink."', {$jsData});".
                    "clearInterval(widgetTimer{$id});".
                '}'.
            '}, 100);';
    }

    /**
     * Construct ajax call without jquery.
     *
     * @param array $data
     *
     * @return string
     */
    protected function constructNativeJsAjaxCall(array $data)
    {
        $jsData = "'id=' + encodeURIComponent('{$data['id']}')".
                "'&name=' + encodeURIComponent('{$data['name']}')".
                "'&params=' + encodeURIComponent('{$data['params']}')".
                "'&_token=' + encodeURIComponent('{$data['token']})";

        return
            'var xhr = new XMLHttpRequest();'.
            'var params = '.$jsData.';'.
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
