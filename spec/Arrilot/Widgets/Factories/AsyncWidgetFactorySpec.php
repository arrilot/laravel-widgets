<?php

namespace spec\Arrilot\Widgets\Factories;

use Arrilot\Widgets\Misc\Wrapper;
use Arrilot\Widgets\WidgetId;
use PhpSpec\ObjectBehavior;

class AsyncWidgetFactorySpec extends ObjectBehavior
{
    protected $config = [
        'defaultNamespace' => 'App\Widgets',
        'customNamespaces' => [
            'slider'          => 'spec\Arrilot\Widgets\Dummies',
            'testWidgetName'  => '',
        ],
    ];

    /**
     * A mock for producing JS object for ajax.
     *
     * @param $widgetName
     * @param array $widgetParams
     * @param int   $id
     *
     * @return string
     */
    private function mockProduceJavascriptData($widgetName, $widgetParams = [], $id = 1)
    {
        return json_encode([
            'id'     => $id,
            'name'   => $widgetName,
            'params' => serialize($widgetParams),
            '_token' => 'token_stub',
        ]);
    }

    public function let(Wrapper $wrapper)
    {
        $this->beConstructedWith($this->config, $wrapper);
        WidgetId::reset();
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Arrilot\Widgets\Factories\AsyncWidgetFactory');
    }

    public function it_can_run_async_widget(Wrapper $wrapper)
    {
        $config = ['count' => 5];
        $params = [$config];

        $wrapper->csrf_token()->willReturn('token_stub');

        $this->testDefaultSlider($config)
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">'.
                "<script type=\"text/javascript\">$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".$this->mockProduceJavascriptData('TestDefaultSlider', $params).')</script>'.
                '</div>'
            );
    }

    public function it_can_run_async_widget_with_placeholder(Wrapper $wrapper)
    {
        $config = ['count' => 5];
        $params = [$config];

        $wrapper->csrf_token()->willReturn('token_stub');

        $this->slider($config)
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
                "<script type=\"text/javascript\">$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".$this->mockProduceJavascriptData('Slider', $params).')</script>'.
                '</div>'
            );
    }

    public function it_can_run_multiple_async_widgets(Wrapper $wrapper)
    {
        $config = ['count' => 5];
        $params = [$config];

        $wrapper->csrf_token()->willReturn('token_stub');

        $this->slider()
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
                "<script type=\"text/javascript\">$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".$this->mockProduceJavascriptData('Slider').')</script>'.
                '</div>'
            );

        $this->testDefaultSlider($config)
            ->shouldReturn(
                '<div id="arrilot-widget-container-2" style="display:inline" class="arrilot-widget-container">'.
                "<script type=\"text/javascript\">$('#arrilot-widget-container-2').load('/arrilot/load-widget', ".$this->mockProduceJavascriptData('TestDefaultSlider', $params, 2).')</script>'.
                '</div>'
            );
    }

    public function it_can_run_async_widget_with_additional_params(Wrapper $wrapper)
    {
        $params = [
            [],
            'param',
        ];

        $wrapper->csrf_token()->willReturn('token_stub');

        $this->testWidgetWithParamsInRun([], 'param')
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Placeholder here!'.
                "<script type=\"text/javascript\">$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".$this->mockProduceJavascriptData('TestWidgetWithParamsInRun', $params).')</script>'.
                '</div>'
            );
    }

    public function it_can_run_async_widget_with_run_method(Wrapper $wrapper)
    {
        $config = ['count' => 5];
        $params = [$config];

        $wrapper->csrf_token()->willReturn('token_stub');

        $this->run('testDefaultSlider', $config)
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">'.
                "<script type=\"text/javascript\">$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".$this->mockProduceJavascriptData('TestDefaultSlider', $params).')</script>'.
                '</div>'
            );
    }

    public function it_can_run_nested_async_widget(Wrapper $wrapper)
    {
        $config = ['count' => 5];
        $params = [$config];

        $wrapper->csrf_token()->willReturn('token_stub');

        $this->run('Profile\TestNamespace\TestFeed', $config)
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">'.
                "<script type=\"text/javascript\">$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".$this->mockProduceJavascriptData('Profile\TestNamespace\TestFeed', $params).')</script>'.
                '</div>'
            );
    }

    public function it_can_run_nested_async_widget_with_dot_notation(Wrapper $wrapper)
    {
        $config = ['count' => 5];
        $params = [$config];

        $wrapper->csrf_token()->willReturn('token_stub');

        $this->run('profile.testNamespace.testFeed', $config)
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">'.
                "<script type=\"text/javascript\">$('#arrilot-widget-container-1').load('/arrilot/load-widget', ".$this->mockProduceJavascriptData('Profile\testNamespace\testFeed', $params).')</script>'.
                '</div>'
            );
    }
}
