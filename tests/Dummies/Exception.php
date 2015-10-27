<?php

namespace Arrilot\Widgets\Test\Dummies;

use Arrilot\Widgets\AbstractWidget;

class Exception extends AbstractWidget
{
    public function run()
    {
        return 'Exception widget was executed instead of predefined php class';
    }
}
