<?php

namespace spec\Arrilot\Widgets;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WidgetFactorySpec extends ObjectBehavior
{
    protected $default_namespace = 'App\Widgets';


    function it_is_initializable()
    {
        $this->shouldHaveType('Arrilot\Widgets\WidgetFactory');
    }


    function it_determines_default_namespace()
    {
        $widgetName = "testWidgetName";
        $customNamespaces = [];
        $this->determineNamespace($widgetName, $customNamespaces, $this->default_namespace)
             ->shouldReturn($this->default_namespace);
    }


    function it_determines_custom_namespace()
    {
        $widgetName = "testWidgetName";
        $customNamespaces = [
            'testWidgetName' => 'Acme\Widgets',
            'test2'          => 'Another\Namespace'
        ];
        $this->determineNamespace($widgetName, $customNamespaces, $this->default_namespace)
            ->shouldReturn($customNamespaces[$widgetName]);
    }


    function it_determines_empty_namespace()
    {
        $widgetName = "testWidgetName";
        $customNamespaces = [
            'testWidgetName' => '',
            'test2'          => 'Another\Namespace'
        ];
        $this->determineNamespace($widgetName, $customNamespaces, $this->default_namespace)
            ->shouldReturn('');
    }
}
