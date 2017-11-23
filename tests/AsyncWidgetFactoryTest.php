<?php

namespace Arrilot\Widgets\Test;

use Arrilot\Widgets\Factories\AsyncWidgetFactory;
use Arrilot\Widgets\Test\Support\TestApplicationWrapper;
use Arrilot\Widgets\Test\Support\TestCase;

class AsyncWidgetFactoryTest extends TestCase
{
    /**
     * @var AsyncWidgetFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new AsyncWidgetFactory(new TestApplicationWrapper());
    }

    public function testItCanRunAsyncWidget()
    {
        $output = $this->factory->run('testDefaultSlider');

        $expected =
            '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">'.
                '<script type="text/javascript">'.
                    'var widgetTimer1 = setInterval(function() {'.
                        'if (window.$) {'.
                            "$('#arrilot-widget-container-1').load('".$this->ajaxUrl('TestDefaultSlider')."');".
                            'clearInterval(widgetTimer1);'.
                        '}'.
                    '}, 100);'.
                '</script>'.
            '</div>';

        $this->assertEquals($expected, $output);
    }

    public function testItCanRunAsyncWidgetWithPlaceholder()
    {
        $output = $this->factory->run('slider');

        $expected =
            '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
                '<script type="text/javascript">'.
                    'var widgetTimer1 = setInterval(function() {'.
                        'if (window.$) {'.
                            "$('#arrilot-widget-container-1').load('".$this->ajaxUrl('Slider')."');".
                            'clearInterval(widgetTimer1);'.
                        '}'.
                    '}, 100);'.
                '</script>'.
            '</div>';

        $this->assertEquals($expected, $output);
    }

    public function testItCanRunMultipleAsyncWidgets()
    {
        $output = $this->factory->run('slider');

        $expected =
            '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
                '<script type="text/javascript">'.
                    'var widgetTimer1 = setInterval(function() {'.
                        'if (window.$) {'.
                            "$('#arrilot-widget-container-1').load('".$this->ajaxUrl('Slider')."');".
                            'clearInterval(widgetTimer1);'.
                        '}'.
                    '}, 100);'.
                '</script>'.
            '</div>';

        $this->assertEquals($expected, $output);

        $output = $this->factory->run('testDefaultSlider');

        $expected =
            '<div id="arrilot-widget-container-2" style="display:inline" class="arrilot-widget-container">'.
                '<script type="text/javascript">'.
                    'var widgetTimer2 = setInterval(function() {'.
                        'if (window.$) {'.
                            "$('#arrilot-widget-container-2').load('".$this->ajaxUrl('TestDefaultSlider', [], 2)."');".
                            'clearInterval(widgetTimer2);'.
                        '}'.
                    '}, 100);'.
                '</script>'.
            '</div>';

        $this->assertEquals($expected, $output);
    }

    public function testItCanRunAsyncWidgetWithAdditionalParams()
    {
        $params = [
            [],
            'parameter',
        ];

        $output = $this->factory->run('testWidgetWithParamsInRun', [], 'parameter');

        $expected =
            '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
                '<script type="text/javascript">'.
                    'var widgetTimer1 = setInterval(function() {'.
                        'if (window.$) {'.
                            "$('#arrilot-widget-container-1').load('".$this->ajaxUrl('TestWidgetWithParamsInRun', $params)."');".
                            'clearInterval(widgetTimer1);'.
                        '}'.
                    '}, 100);'.
                '</script>'.
            '</div>';

        $this->assertEquals($expected, $output);
    }

    public function testItCanRunAsyncWidgetWithMagicMethod()
    {
        $output = $this->factory->slider();

        $expected =
            '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
                '<script type="text/javascript">'.
                    'var widgetTimer1 = setInterval(function() {'.
                        'if (window.$) {'.
                            "$('#arrilot-widget-container-1').load('".$this->ajaxUrl('Slider')."');".
                            'clearInterval(widgetTimer1);'.
                        '}'.
                    '}, 100);'.
                '</script>'.
            '</div>';

        $this->assertEquals($expected, $output);
    }

    public function testItCanRunNestedAsyncWidget()
    {
        $output = $this->factory->run('Profile\TestNamespace\TestFeed');

        $expected =
            '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">'.
                '<script type="text/javascript">'.
                    'var widgetTimer1 = setInterval(function() {'.
                        'if (window.$) {'.
                            "$('#arrilot-widget-container-1').load('".$this->ajaxUrl('Profile\TestNamespace\TestFeed')."');".
                            'clearInterval(widgetTimer1);'.
                        '}'.
                    '}, 100);'.
                '</script>'.
            '</div>';

        $this->assertEquals($expected, $output);
    }

    public function testItCanRunNestedAsyncWidgetUsingDotNotation()
    {
        $output = $this->factory->run('profile.testNamespace.testFeed');

        $expected =
            '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">'.
                '<script type="text/javascript">'.
                    'var widgetTimer1 = setInterval(function() {'.
                        'if (window.$) {'.
                            "$('#arrilot-widget-container-1').load('".$this->ajaxUrl('Profile\TestNamespace\TestFeed')."');".
                            'clearInterval(widgetTimer1);'.
                        '}'.
                    '}, 100);'.
                '</script>'.
            '</div>';

        $this->assertEquals($expected, $output);
    }

    public function testItCanRunAsyncWidgetWithoutJquery()
    {
        $this->factory->app->config['laravel-widgets.use_jquery_for_ajax_calls'] = false;

        $output = $this->factory->run('testDefaultSlider');

        $expected =
            '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">'.
                '<script type="text/javascript">'.
                    'setTimeout(function() {'.
                        'var xhr = new XMLHttpRequest();'.
                        'xhr.open("GET", "'.$this->ajaxUrl('TestDefaultSlider').'", true);'.
                        'xhr.onreadystatechange = function() {'.
                            'if(xhr.readyState == 4 && xhr.status == 200) {'.
                                'var container = document.getElementById("arrilot-widget-container-1");'.
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
                    '}, 0);'.
                '</script>'.
            '</div>';
        $this->assertEquals($expected, $output);
    }
}
