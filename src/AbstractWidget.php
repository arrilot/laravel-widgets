<?php

namespace Arrilot\Widgets;

abstract class AbstractWidget
{
    /**
     * Id for async widget.
     *
     * @var int
     */
    public static $incrementingId = 0;

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

    /**
     * Resets the incrementing id to 0.
     *
     * @return string
     */
    public static function resetId()
    {
        self::$incrementingId = 0;
    }
}
