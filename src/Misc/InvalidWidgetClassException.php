<?php

namespace Arrilot\Widgets\Misc;

use Exception;

class InvalidWidgetClassException extends Exception
{
    /**
     * Exception message.
     *
     * @var string
     */
    protected $message = 'Widget class must extend Arrilot\Widgets\AbstractWidget class';
}
