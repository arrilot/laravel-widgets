<?php namespace spec\Arrilot\Widgets;

require "Dummies/DefaultTestSlider.php";
require "Dummies/Slider.php";
require "Dummies/BadTestSlider.php";

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


    function let()
    {
        $this->beConstructedWith($this->config);
    }


    function it_is_initializable()
    {
        $this->shouldHaveType('Arrilot\Widgets\WidgetFactory');
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
