<?php

namespace spec\Arrilot\Widgets\Factories;

use Arrilot\Widgets\AbstractWidget;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AsyncWidgetFactorySpec extends ObjectBehavior
{
    protected $config = [
        'defaultNamespace' => 'App\Widgets',
        'customNamespaces' => [
            'slider'          => 'spec\Arrilot\Widgets\Dummies',
            'testWidgetName'  => ''
        ]
    ];


    function let()
    {
        $this->beConstructedWith($this->config);
        AbstractWidget::resetId();
    }


    function it_is_initializable()
    {
        $this->shouldHaveType('Arrilot\Widgets\Factories\AsyncWidgetFactory');
    }


    function it_can_run_async_widget()
    {
        $config = ['count' => 5];
        $ajaxLink = $this->mockGetAjaxLink('defaultTestSlider', $config);

        $this->defaultTestSlider($config)->shouldReturn("<div id='async-widget-container-1'></div><script>$('#async-widget-container-1').load('{$ajaxLink}')</script>");
    }


    function it_can_run_async_widget_with_placeholder()
    {
        $config = ['count' => 5];
        $ajaxLink = $this->mockGetAjaxLink('slider', $config);

        $this->slider($config)->shouldReturn("<div id='async-widget-container-1'>Placeholder here!</div><script>$('#async-widget-container-1').load('{$ajaxLink}')</script>");
    }


    function it_can_run_multiple_async_widgets()
    {
        $config = ['count' => 5];
        $ajaxLink = $this->mockGetAjaxLink('slider', $config);
        $ajaxLink2 = $this->mockGetAjaxLink('defaultTestSlider', $config);

        $this->slider($config)->shouldReturn("<div id='async-widget-container-1'>Placeholder here!</div><script>$('#async-widget-container-1').load('{$ajaxLink}')</script>");
        $this->defaultTestSlider($config)->shouldReturn("<div id='async-widget-container-2'></div><script>$('#async-widget-container-2').load('{$ajaxLink2}')</script>");

    }


    /**
     * A mock for protected method.
     *
     * @param $widgetName
     * @param $config
     * @return string
     */
    protected function mockGetAjaxLink($widgetName, $config)
    {
        $query    = http_build_query([
            'widget' => [
                'name'   => $widgetName,
                'config' => $config
            ]
        ]);
        $ajaxLink = '/arrilot/async-widget?' . $query;

        return $ajaxLink;
    }
}
