<?php

namespace Arrilot\Widgets;

abstract class AbstractWidget
{
    /**
     * The number of seconds before each reload.
     *
     * @var int|float
     */
    public $reloadTimeout;

    /**
     * Constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        if (!empty($config)) {
            foreach ($config as $property => $value) {
                if (property_exists($this, $property)) {
                    $this->$property = $value;
                }
            }
        }
    }

    /**
     * Placeholder for async widget.
     *
     * @return string
     */
    public function placeholder()
    {
        return '';
    }
}
