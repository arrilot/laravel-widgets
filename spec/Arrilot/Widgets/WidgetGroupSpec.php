<?php

namespace spec\Arrilot\Widgets;

use PhpSpec\ObjectBehavior;

class WidgetGroupSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Arrilot\Widgets\WidgetGroup');
    }

    public function let()
    {
        $this->beConstructedWith('sidebar');
    }

    public function it_allows_to_add_widgets()
    {
        $this->addWidget('slider1');
        $this->position(50)->addAsyncWidget('slider2');

        $this->getWidgets()->shouldHaveCount(2);
        $this->getPosition()->shouldBe(100);
    }
}
