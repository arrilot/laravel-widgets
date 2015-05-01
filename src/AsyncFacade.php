<?php

namespace Arrilot\Widgets;

class AsyncFacade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'arrilot.async-widget';
    }
}
