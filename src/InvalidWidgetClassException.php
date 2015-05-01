<?php

namespace Arrilot\Widgets;

class InvalidWidgetClassException extends \Exception
{
    /**
     * Exception message.
     *
     * @var string
     */
    protected $message = 'Widget class must extend Arrilot\Widgets\AbstractWidget class';
}
