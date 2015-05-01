<?php

namespace Arrilot\Widgets\Misc;

class InvalidWidgetClassException extends \Exception
{
    /**
     * Exception message.
     *
     * @var string
     */
    protected $message = 'Widget class must extend Arrilot\Widgets\AbstractWidget class';
}
