<?php

namespace spec\Arrilot\Widgets\Factories;

use App\Widgets\Profile\TestNamespace\TestFeed;
use App\Widgets\TestDefaultSlider;
use App\Widgets\TestMyClass;
use App\Widgets\TestRepeatableFeed;
use App\Widgets\TestWidgetWithDIInRun;
use App\Widgets\TestWidgetWithParamsInRun;
use App\Widgets\TestWidgetWithCustomCssClass;
use Arrilot\Widgets\Misc\Wrapper;
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

    public function let(Wrapper $wrapper)
    {
        $this->beConstructedWith($this->config, $wrapper);
        WidgetId::reset();
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Arrilot\Widgets\Factories\WidgetFactory');
    }

    public function it_can_run_widget_from_default_namespace(Wrapper $wrapper)
    {
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestDefaultSlider([]), 'run'], [])
        );
        $this->testDefaultSlider()
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Default test slider was executed with $slides = 6</div>'
            );
    }

    public function it_can_run_widget_from_custom_namespace(Wrapper $wrapper)
    {
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new Slider([]), 'run'], [])
        );
        $this->slider()
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Slider was executed with $slides = 6</div>'
            );
    }

    public function it_provides_config_override(Wrapper $wrapper)
    {
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new Slider(['slides' => 5]), 'run'], ['slides' => 5])
        );
        $this->slider(['slides' => 5])
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Slider was executed with $slides = 5</div>'
            );
    }

    public function it_throws_exception_for_bad_widget_class()
    {
        $this->shouldThrow('\Arrilot\Widgets\Misc\InvalidWidgetClassException')->during('testBadSlider');
    }

    public function it_can_run_widgets_with_additional_params(Wrapper $wrapper)
    {
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestWidgetWithParamsInRun([]), 'run'], ['asc'])
        );
        $this->testWidgetWithParamsInRun([], 'asc')
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">TestWidgetWithParamsInRun was executed with $flag = asc</div>'
            );
    }

    public function it_can_run_widgets_with_method_injection(Wrapper $wrapper)
    {
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestWidgetWithDIInRun([]), 'run'], [new TestMyClass()])
        );
        $this->testWidgetWithParamsInRun()
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">bar</div>'
            );
    }

    public function it_can_run_widgets_with_run_method(Wrapper $wrapper)
    {
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestDefaultSlider([]), 'run'], [])
        );
        $this->run('testDefaultSlider')
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Default test slider was executed with $slides = 6</div>'
            );
    }

    public function it_can_run_widgets_with_run_method_and_config_override(Wrapper $wrapper)
    {
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new Slider(['slides' => 5]), 'run'], ['slides' => 5])
        );
        $this->run('slider', ['slides' => 5])
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Slider was executed with $slides = 5</div>'
            );
    }

    public function it_can_run_nested_widgets(Wrapper $wrapper)
    {
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestFeed([]), 'run'], [])
        );
        $this->run('Profile\TestNamespace\TestFeed', ['slides' => 5])
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Feed was executed with $slides = 6</div>'
            );
    }

    public function it_can_run_nested_widgets_with_dot_notation(Wrapper $wrapper)
    {
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestFeed([]), 'run'], [])
        );
        $this->run('profile.testNamespace.testFeed', ['slides' => 5])
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Feed was executed with $slides = 6</div>'
            );
    }

    public function it_can_run_multiple_widgets(Wrapper $wrapper)
    {
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new Slider([]), 'run'], [])
        );
        $this->slider()
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Slider was executed with $slides = 6</div>'
            );

        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new Slider(['slides' => 5]), 'run'], ['slides' => 5])
        );
        $this->slider(['slides' => 5])
            ->shouldReturn(
                '<div id="arrilot-widget-container-2" style="display:inline" class="arrilot-widget-container">Slider was executed with $slides = 5</div>'
            );
    }

    public function it_can_run_async_widget(Wrapper $wrapper)
    {
        $config = [];
        $params = [$config];

        $wrapper->csrf_token()->willReturn('token_stub');
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestRepeatableFeed([]), 'run'], [])
        );

        $this->testRepeatableFeed($config)
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="arrilot-widget-container">Feed was executed with $slides = 6'.
                '<script type="text/javascript">setTimeout( function() { $(\'#arrilot-widget-container-1\').load(\'/arrilot/load-widget\', '.$this->mockProduceJavascriptData('TestRepeatableFeed', $params).') }, 10000)</script>'.
                '</div>'
            );
    }

    public function it_can_be_configurate_to_use_custom_css_class_in_wrapper(Wrapper $wrapper)
    {
        $wrapper->appCall(Argument::any(), Argument::any())->willReturn(
            call_user_func_array([new TestWidgetWithCustomCssClass([]), 'run'], [])
        );
        $this->run('testWidgetWithCustomCssClass')
            ->shouldReturn(
                '<div id="arrilot-widget-container-1" style="display:inline" class="dummyClass">Dummy Content</div>'
            );
    }
}
