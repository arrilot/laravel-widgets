<?php namespace Arrilot\Widgets;

class InvalidWidgetClassException extends \Exception {

    protected $message = 'Widget class must extend Arrilot\Widgets\AbstractWidget class';
}
