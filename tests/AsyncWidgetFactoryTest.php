<?php

namespace Arrilot\Widgets\Test;

use Arrilot\Widgets\Factories\AsyncWidgetFactory;
use Arrilot\Widgets\WidgetId;
use PHPUnit_Framework_TestCase;

class AsyncWidgetFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AsyncWidgetFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new AsyncWidgetFactory(new TestApplicationWrapper());
    }

    public function tearDown()
    {
        WidgetId::reset();
    }

    public function testItCanRunAsyncWidget()
    {
        $output = $this->factory->run('testDefaultSlider');

        $this->assertEquals('<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">'.
            "<script type=\"text/javascript\">$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".javascript_data_stub('TestDefaultSlider').')</script>'.
            '</div>', $output);
    }

    public function testItCanRunAsyncWidgetWithPlaceholder()
    {
        $output = $this->factory->run('slider');

        $this->assertEquals('<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
            "<script type=\"text/javascript\">$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".javascript_data_stub('Slider').')</script>'.
            '</div>', $output);
    }

    public function testItCanRunMultipleAsyncWidgets()
    {
        $output = $this->factory->run('slider');

        $this->assertEquals('<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
            "<script type=\"text/javascript\">$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".javascript_data_stub('Slider').')</script>'.
            '</div>', $output);

        $output = $this->factory->run('testDefaultSlider');

        $this->assertEquals('<div id="arrilot-widget-container-2" style="display:inline" class="arrilot-widget-container">'.
            "<script type=\"text/javascript\">$('#arrilot-widget-container-2').load('/arrilot/load-widget', ".javascript_data_stub('TestDefaultSlider', [], 2).')</script>'.
            '</div>', $output);
    }

    public function testItCanRunAsyncWidgetWithAdditionalParams()
    {
        $params = [
            [],
            'parameter',
        ];

        $output = $this->factory->run('testWidgetWithParamsInRun', [], 'parameter');

        $this->assertEquals('<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
            "<script type=\"text/javascript\">$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".javascript_data_stub('TestWidgetWithParamsInRun', $params).')</script>'.
            '</div>', $output);
    }

    public function testItCanRunAsyncWidgetWithMagicMethod()
    {
        $output = $this->factory->slider();

        $this->assertEquals('<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
            "<script type=\"text/javascript\">$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".javascript_data_stub('Slider').')</script>'.
            '</div>', $output);
    }

    public function testItCanRunNestedAsyncWidget()
    {
        $output = $this->factory->run('Profile\TestNamespace\TestFeed');

        $this->assertEquals('<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">'.
            "<script type=\"text/javascript\">$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".javascript_data_stub('Profile\TestNamespace\TestFeed').')</script>'.
            '</div>', $output);
    }

    public function testItCanRunNestedAsyncWidgetUsingDotNotation()
    {
        $output = $this->factory->run('profile.testNamespace.testFeed');

        $this->assertEquals('<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">'.
            "<script type=\"text/javascript\">$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".javascript_data_stub('Profile\testNamespace\testFeed').')</script>'.
            '</div>', $output);
    }
}
