<?php

namespace Arrilot\Widgets\Test\Dummies;

use Arrilot\Widgets\AbstractWidget;

class TestDefaultSlider extends AbstractWidget
{
    protected $slides = 6;

    public function run()
    {
        return 'Default test slider was executed with $slides = '.$this->slides;
    }
}
