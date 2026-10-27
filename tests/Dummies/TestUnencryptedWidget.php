<?php

namespace Arrilot\Widgets\Test\Dummies;

use Arrilot\Widgets\AbstractWidget;

class TestUnencryptedWidget extends AbstractWidget
{
    public $encryptParams = false;

    public function run()
    {
        return 'Unencrypted widget output';
    }
}
