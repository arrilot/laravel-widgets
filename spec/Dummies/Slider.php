<?php

namespace spec\Arrilot\Widgets\Dummies;

use Arrilot\Widgets\AbstractWidget;

class Slider extends AbstractWidget
{
    protected $slides = 6;

    public function run()
    {
        return "Slider was executed with \$slides = ".$this->slides;
    }

    public function placeholder()
    {
        return 'Placeholder here!';
    }
}
