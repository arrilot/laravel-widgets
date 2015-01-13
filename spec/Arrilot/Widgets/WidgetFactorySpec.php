<?php

namespace spec\Arrilot\Widgets;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WidgetFactorySpec extends ObjectBehavior
{
    public $configDefaultNamespace = 'App\Widgets';
    public $configCustomNamespaces = [
        'testWidgetName'  => 'Acme\Widgets',
        'testWidgetName2' => ''
    ];

    function let()
    {
        $this->beConstructedWith($this->configDefaultNamespace, $this->configCustomNamespaces);
    }


    function it_is_initializable()
    {
        $this->shouldHaveType('Arrilot\Widgets\WidgetFactory');
    }


    function it_returns_a_string_by_determine_namesace_method()
    {
        $this->determineNamespace("Slider")->shouldBeString();
    }


    function it_determines_default_namespace()
    {
        $widgetName = "Slider";
        $this->determineNamespace($widgetName)
             ->shouldReturn($this->configDefaultNamespace);
    }


    function it_determines_custom_namespace()
    {
        $widgetName = "testWidgetName";

        $this->determineNamespace($widgetName)
            ->shouldReturn($this->configCustomNamespaces[$widgetName]);
    }


    function it_determines_empty_namespace()
    {
        $widgetName = "testWidgetName2";

        $this->determineNamespace($widgetName)
            ->shouldReturn('');
    }
}
