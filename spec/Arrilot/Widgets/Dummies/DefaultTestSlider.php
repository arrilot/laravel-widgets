<?php namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class DefaultTestSlider extends AbstractWidget {

    protected $slides = 6;

    public function run()
    {
        return "Default test slider was executed with \$slides = " . $this->slides;
    }
}