<?php

namespace Arrilot\Widgets\Test;

use Arrilot\Widgets\Factories\AsyncWidgetFactory;

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

        $this->assertEquals(
            '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">'.
                "<script type=\"text/javascript\">".
                    "var widgetTimer1 = setInterval(function() {".
                        "if (window.$) {".
                            "$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".$this->javascriptDataStub('TestDefaultSlider').");".
                            "clearInterval(widgetTimer1);".
                        "}".
                    "}, 100);".
                "</script>".
            '</div>', $output);
    }

    public function testItCanRunAsyncWidgetWithPlaceholder()
    {
        $output = $this->factory->run('slider');

        $this->assertEquals(
            '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
                "<script type=\"text/javascript\">".
                    "var widgetTimer1 = setInterval(function() {".
                        "if (window.$) {".
                            "$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".$this->javascriptDataStub('Slider').");".
                            "clearInterval(widgetTimer1);".
                        "}".
                    "}, 100);".
                "</script>".
            '</div>', $output);
    }

    public function testItCanRunMultipleAsyncWidgets()
    {
        $output = $this->factory->run('slider');

        $this->assertEquals(
            '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
                "<script type=\"text/javascript\">".
                    "var widgetTimer1 = setInterval(function() {".
                        "if (window.$) {".
                            "$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".$this->javascriptDataStub('Slider').");".
                            "clearInterval(widgetTimer1);".
                        "}".
                    "}, 100);".
                "</script>".
            '</div>', $output);

        $output = $this->factory->run('testDefaultSlider');

        $this->assertEquals(
            '<div id="arrilot-widget-container-2" style="display:inline" class="arrilot-widget-container">'.
                "<script type=\"text/javascript\">".
                    "var widgetTimer2 = setInterval(function() {".
                        "if (window.$) {".
                            "$('#arrilot-widget-container-2').load('/arrilot/load-widget', ".$this->javascriptDataStub('TestDefaultSlider', [], 2).");".
                            "clearInterval(widgetTimer2);".
                        "}".
                    "}, 100);".
                "</script>".
            '</div>', $output);
    }

    public function testItCanRunAsyncWidgetWithAdditionalParams()
    {
        $params = [
            [],
            'parameter',
        ];

        $output = $this->factory->run('testWidgetWithParamsInRun', [], 'parameter');
        $this->assertEquals(
            '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
                "<script type=\"text/javascript\">".
                    "var widgetTimer1 = setInterval(function() {".
                        "if (window.$) {".
                            "$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".$this->javascriptDataStub('TestWidgetWithParamsInRun', $params).");".
                            "clearInterval(widgetTimer1);".
                        "}".
                    "}, 100);".
                "</script>".
            '</div>', $output);
    }

    public function testItCanRunAsyncWidgetWithMagicMethod()
    {
        $output = $this->factory->slider();

        $this->assertEquals(
            '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
                "<script type=\"text/javascript\">".
                    "var widgetTimer1 = setInterval(function() {".
                        "if (window.$) {".
                            "$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".$this->javascriptDataStub('Slider').");".
                            "clearInterval(widgetTimer1);".
                        "}".
                    "}, 100);".
                "</script>".
            '</div>', $output);
    }

    public function testItCanRunNestedAsyncWidget()
    {
        $output = $this->factory->run('Profile\TestNamespace\TestFeed');

        $this->assertEquals(
            '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">'.
                "<script type=\"text/javascript\">".
                    "var widgetTimer1 = setInterval(function() {".
                        "if (window.$) {".
                            "$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".$this->javascriptDataStub('Profile\TestNamespace\TestFeed').");".
                            "clearInterval(widgetTimer1);".
                        "}".
                    "}, 100);".
                "</script>".
            '</div>', $output);
    }

    public function testItCanRunNestedAsyncWidgetUsingDotNotation()
    {
        $output = $this->factory->run('profile.testNamespace.testFeed');

        $this->assertEquals(
            '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">'.
                "<script type=\"text/javascript\">".
                    "var widgetTimer1 = setInterval(function() {".
                        "if (window.$) {".
                            "$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".$this->javascriptDataStub('Profile\testNamespace\testFeed').");".
                            "clearInterval(widgetTimer1);".
                        "}".
                    "}, 100);".
                "</script>".
            '</div>', $output);
    }
}
