<?php

namespace spec\Arrilot\Widgets\Factories;

use App\Widgets\Profile\TestNamespace\TestFeed;
use App\Widgets\TestCachedWidget;
use App\Widgets\TestDefaultSlider;
use App\Widgets\TestMyClass;
use App\Widgets\TestRepeatableFeed;
use App\Widgets\TestWidgetWithCustomContainer;
use App\Widgets\TestWidgetWithDIInRun;
use App\Widgets\TestWidgetWithParamsInRun;
use Arrilot\Widgets\Misc\LaravelApplicationWrapper;
use Arrilot\Widgets\WidgetId;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\Arrilot\Widgets\Dummies\Slider;

class WidgetFactorySpec extends ObjectBehavior
{
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
            'id'     => 1,
            'name'   => $widgetName,
            'params' => serialize($widgetParams),
            '_token' => 'token_stub',
        ]);
    }

    protected $config = [
        'defaultNamespace' => 'App\Widgets',
        'customNamespaces' => [
                'slider'             => 'spec\Arrilot\Widgets\Dummies',
                'testRepeatableFeed' => 'spec\Arrilot\Widgets\Dummies',
                'testWidgetName'     => '',
            ],
        ];

    public function let(LaravelApplicationWrapper $wrapper)
    {
        $this->beConstructedWith($this->config, $wrapper);
        WidgetId::reset();
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Arrilot\Widgets\Factories\WidgetFactory');
    }

    public function it_can_run_widget_from_default_namespace(LaravelApplicationWrapper $wrapper)
    {
        $wrapper->call(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestDefaultSlider([]), 'run'], [])
        );
        $this->testDefaultSlider()
            ->shouldReturn(
                'Default test slider was executed with $slides = 6'
            );
    }

    public function it_can_run_widget_from_custom_namespace(LaravelApplicationWrapper $wrapper)
    {
        $wrapper->call(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new Slider([]), 'run'], [])
        );
        $this->slider()
            ->shouldReturn(
                'Slider was executed with $slides = 6'
            );
    }

    public function it_provides_config_override(LaravelApplicationWrapper $wrapper)
    {
        $wrapper->call(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new Slider(['slides' => 5]), 'run'], ['slides' => 5])
        );
        $this->slider(['slides' => 5])
            ->shouldReturn(
                'Slider was executed with $slides = 5'
            );
    }

    public function it_throws_exception_for_bad_widget_class()
    {
        $this->shouldThrow('\Arrilot\Widgets\Misc\InvalidWidgetClassException')->during('testBadSlider');
    }

    public function it_can_run_widgets_with_additional_params(LaravelApplicationWrapper $wrapper)
    {
        $wrapper->call(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestWidgetWithParamsInRun([]), 'run'], ['asc'])
        );
        $this->testWidgetWithParamsInRun([], 'asc')
            ->shouldReturn(
                'TestWidgetWithParamsInRun was executed with $flag = asc'
            );
    }

    public function it_can_run_widgets_with_method_injection(LaravelApplicationWrapper $wrapper)
    {
        $wrapper->call(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestWidgetWithDIInRun([]), 'run'], [new TestMyClass()])
        );
        $this->testWidgetWithParamsInRun()
            ->shouldReturn(
                'bar'
            );
    }

    public function it_can_run_widgets_with_run_method(LaravelApplicationWrapper $wrapper)
    {
        $wrapper->call(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestDefaultSlider([]), 'run'], [])
        );
        $this->run('testDefaultSlider')
            ->shouldReturn(
                'Default test slider was executed with $slides = 6'
            );
    }

    public function it_can_run_widgets_with_run_method_and_config_override(LaravelApplicationWrapper $wrapper)
    {
        $wrapper->call(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new Slider(['slides' => 5]), 'run'], ['slides' => 5])
        );
        $this->run('slider', ['slides' => 5])
            ->shouldReturn(
                'Slider was executed with $slides = 5'
            );
    }

    public function it_can_run_nested_widgets(LaravelApplicationWrapper $wrapper)
    {
        $wrapper->call(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestFeed([]), 'run'], [])
        );
        $this->run('Profile\TestNamespace\TestFeed', ['slides' => 5])
            ->shouldReturn(
                'Feed was executed with $slides = 6'
            );
    }

    public function it_can_run_nested_widgets_with_dot_notation(LaravelApplicationWrapper $wrapper)
    {
        $wrapper->call(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestFeed([]), 'run'], [])
        );
        $this->run('profile.testNamespace.testFeed', ['slides' => 5])
            ->shouldReturn(
                'Feed was executed with $slides = 6'
            );
    }

    public function it_can_run_multiple_widgets(LaravelApplicationWrapper $wrapper)
    {
        $wrapper->call(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new Slider([]), 'run'], [])
        );
        $this->slider()
            ->shouldReturn(
                'Slider was executed with $slides = 6'
            );

        $wrapper->call(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new Slider(['slides' => 5]), 'run'], ['slides' => 5])
        );
        $this->slider(['slides' => 5])
            ->shouldReturn(
                'Slider was executed with $slides = 5'
            );
    }

    public function it_can_run_reloadable_widget(LaravelApplicationWrapper $wrapper)
    {
        $config = [];
        $params = [$config];

        $wrapper->csrf_token()->willReturn('token_stub');
        $wrapper->call(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestRepeatableFeed([]), 'run'], [])
        );

        $this->testRepeatableFeed($config)
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Feed was executed with $slides = 6'.
                '<script type="text/javascript">setTimeout( function() { $(\'#arrilot-widget-container-1\').load(\'/arrilot/load-widget\', '.$this->mockProduceJavascriptData('TestRepeatableFeed', $params).') }, 10000)</script>'.
                '</div>'
            );
    }

    public function it_can_run_widget_with_custom_container(LaravelApplicationWrapper $wrapper)
    {
        $config = [];
        $params = [$config];

        $wrapper->csrf_token()->willReturn('token_stub');
        $wrapper->call(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestWidgetWithCustomContainer([]), 'run'], [])
        );

        $this->testWidgetWithCustomContainer($config)
            ->shouldReturn(
                '<p id="arrilot-widget-container-1" data-id="123">Dummy Content'.
                '<script type="text/javascript">setTimeout( function() { $(\'#arrilot-widget-container-1\').load(\'/arrilot/load-widget\', '.$this->mockProduceJavascriptData('TestWidgetWithCustomContainer', $params).') }, 10000)</script>'.
                '</p>'
            );
    }

    public function it_can_cache_widgets(LaravelApplicationWrapper $wrapper)
    {
        $wrapper->call(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestCachedWidget(['slides' => 5]), 'run'], [])
        );
        $wrapper->cache(Argument::any(), Argument::any(), Argument::any())->shouldBeCalled();

        $this->run('testCachedWidget', ['slides' => 5]);
    }
}
