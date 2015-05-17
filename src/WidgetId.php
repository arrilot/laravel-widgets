<?php

namespace Arrilot\Widgets;

class WidgetId
{
    /**
     * Static incrementing widget id.
     *
     * @var int
     */
    protected static $id = 0;

    /**
     * Getter for widget id.
     *
     * @return int
     */
    public static function get()
    {
        return self::$id;
    }

    /**
     * Setter for widget id.
     *
     * @param int $id
     */
    public static function set($id)
    {
        self::$id = $id;
    }

    /**
     * Increment widget id by one.
     */
    public static function increment()
    {
        self::$id++;
    }

    /**
     * Resets widget id to zero.
     */
    public static function reset()
    {
        self::$id = 0;
    }
}
