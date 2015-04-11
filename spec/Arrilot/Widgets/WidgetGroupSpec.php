<?php

namespace spec\Arrilot\Widgets;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WidgetGroupSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Arrilot\Widgets\WidgetGroup');
    }

    function let()
    {
        $this->beConstructedWith('sidebar');
    }

    function it_allows_to_add_widgets()
    {
        $this->addWidget('slider1');
        $this->position(50)->addAsyncWidget('slider2');

        $this->getWidgets()->shouldHaveCount(2);
        $this->getPosition()->shouldBe(100);
    }
}

