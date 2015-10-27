<?php

namespace Arrilot\Widgets\Test\Dummies;

use Arrilot\Widgets\AbstractWidget;

class TestWidgetWithParamsInRun extends AbstractWidget
{
    public function run($flag)
    {
        return 'TestWidgetWithParamsInRun was executed with $flag = '.$flag;
    }

    public function placeholder()
    {
        return 'Placeholder here!';
    }
}
