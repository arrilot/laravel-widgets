<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class TestWidgetWithCustomCssClass extends AbstractWidget
{
    public $cssClassForWrapper = 'dummyClass';

    public function run()
    {
        return 'Dummy Content';
    }

    public function placeholder()
    {
        return 'Placeholder here!';
    }
}
