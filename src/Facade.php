<?php

namespace Arrilot\Widgets;

class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'arrilot.widget';
    }
}
