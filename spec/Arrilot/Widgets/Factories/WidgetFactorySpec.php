<?php namespace spec\Arrilot\Widgets\Factories;

use Arrilot\Widgets\Misc\Wrapper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WidgetFactorySpec extends ObjectBehavior {

    protected $config = [
        'defaultNamespace' => 'App\Widgets',
        'customNamespaces' => [
                'slider'          => 'spec\Arrilot\Widgets\Dummies',
                'testWidgetName'  => ''
            ]
        ];


    function let(Wrapper $wrapper)
    {
        $this->beConstructedWith($this->config, $wrapper);
    }


    function it_is_initializable()
    {
        $this->shouldHaveType('Arrilot\Widgets\Factories\WidgetFactory');
    }


    function it_can_run_widget_from_default_namespace()
    {
        $this->defaultTestSlider()->shouldReturn("Default test slider was executed with \$slides = 6");
    }


    function it_can_run_widget_from_custom_namespace()
    {
        $this->slider()->shouldReturn("Slider was executed with \$slides = 6");
    }


    function it_provides_config_override()
    {
        $this->slider(['slides' => 5])->shouldReturn("Slider was executed with \$slides = 5");
    }


    function it_throws_exception_for_bad_widget_class()
    {
        $this->shouldThrow('\Arrilot\Widgets\InvalidWidgetClassException')->during('badTestSlider');
    }

}
