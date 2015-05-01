<?php

namespace spec\Arrilot\Widgets\Factories;

use Arrilot\Widgets\AbstractWidget;
use Arrilot\Widgets\Misc\Wrapper;
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
     * @param $widgetParams
     *
     * @return string
     */
    private function mockProduceJavascriptData($widgetName, $widgetParams = [])
    {
        return json_encode([
            'name'   => $widgetName,
            'params' => serialize($widgetParams),
            '_token' => 'token_stub',
        ]);
    }

    public function let(Wrapper $wrapper)
    {
        $this->beConstructedWith($this->config, $wrapper);
        AbstractWidget::resetId();
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
            ->shouldReturn("<span id='async-widget-container-1'><script>$.post('/arrilot/async-widget', ".$this->mockProduceJavascriptData('TestDefaultSlider', $params).", function(data) { $('#async-widget-container-1').replaceWith(data); })</script></span>");
    }

    public function it_can_run_async_widget_with_placeholder(Wrapper $wrapper)
    {
        $config = ['count' => 5];
        $params = [$config];

        $wrapper->csrf_token()->willReturn('token_stub');

        $this->slider($config)
            ->shouldReturn("<span id='async-widget-container-1'>Placeholder here!<script>$.post('/arrilot/async-widget', ".$this->mockProduceJavascriptData('Slider', $params).", function(data) { $('#async-widget-container-1').replaceWith(data); })</script></span>");
    }

    public function it_can_run_multiple_async_widgets(Wrapper $wrapper)
    {
        $config = ['count' => 5];
        $params = [$config];

        $wrapper->csrf_token()->willReturn('token_stub');

        $this->slider()
            ->shouldReturn("<span id='async-widget-container-1'>Placeholder here!<script>$.post('/arrilot/async-widget', ".$this->mockProduceJavascriptData('Slider').", function(data) { $('#async-widget-container-1').replaceWith(data); })</script></span>");

        $this->testDefaultSlider($config)
            ->shouldReturn("<span id='async-widget-container-2'><script>$.post('/arrilot/async-widget', ".$this->mockProduceJavascriptData('TestDefaultSlider', $params).", function(data) { $('#async-widget-container-2').replaceWith(data); })</script></span>");
    }

    public function it_can_run_async_widget_with_additional_params(Wrapper $wrapper)
    {
        $params = [
            [],
            'param',
        ];

        $wrapper->csrf_token()->willReturn('token_stub');

        $this->testWidgetWithParamsInRun([], 'param')
            ->shouldReturn("<span id='async-widget-container-1'>Placeholder here!<script>$.post('/arrilot/async-widget', ".$this->mockProduceJavascriptData('TestWidgetWithParamsInRun', $params).", function(data) { $('#async-widget-container-1').replaceWith(data); })</script></span>");
    }

    public function it_can_run_async_widget_with_run_method(Wrapper $wrapper)
    {
        $config = ['count' => 5];
        $params = [$config];

        $wrapper->csrf_token()->willReturn('token_stub');

        $this->run('testDefaultSlider', $config)
            ->shouldReturn("<span id='async-widget-container-1'><script>$.post('/arrilot/async-widget', ".$this->mockProduceJavascriptData('TestDefaultSlider', $params).", function(data) { $('#async-widget-container-1').replaceWith(data); })</script></span>");
    }

    public function it_can_run_nested_async_widget(Wrapper $wrapper)
    {
        $config = ['count' => 5];
        $params = [$config];

        $wrapper->csrf_token()->willReturn('token_stub');

        $this->run('Profile\TestNamespace\TestFeed', $config)
            ->shouldReturn("<span id='async-widget-container-1'><script>$.post('/arrilot/async-widget', ".$this->mockProduceJavascriptData('Profile\TestNamespace\TestFeed', $params).", function(data) { $('#async-widget-container-1').replaceWith(data); })</script></span>");
    }

    public function it_can_run_nested_async_widget_with_dot_notation(Wrapper $wrapper)
    {
        $config = ['count' => 5];
        $params = [$config];

        $wrapper->csrf_token()->willReturn('token_stub');

        $this->run('profile.testNamespace.testFeed', $config)
            ->shouldReturn("<span id='async-widget-container-1'><script>$.post('/arrilot/async-widget', ".$this->mockProduceJavascriptData('Profile\testNamespace\testFeed', $params).", function(data) { $('#async-widget-container-1').replaceWith(data); })</script></span>");
    }
}
