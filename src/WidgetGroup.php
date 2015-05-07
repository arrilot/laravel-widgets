<?php

namespace Arrilot\Widgets;

use Arrilot\Widgets\Misc\Wrapper;

class WidgetGroup
{
    /**
     * The widget group name.
     *
     * @var string
     */
    protected $name;

    /**
     * The array of widgets to display in this group.
     *
     * @var array
     */
    protected $widgets = [];

    /**
     * The position of a widget in this group.
     *
     * @var int
     */
    protected $position = 100;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Display all widgets from this group in correct order.
     *
     * @return string
     */
    public function display()
    {
        $output = '';
        foreach ($this->getSortedWidgets() as $widget) {
            $output .=  $this->displayWidget($widget);
        }

        return $output;
    }

    /**
     * Set widget position.
     *
     * @param int $position
     *
     * @return $this
     */
    public function position($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Add a widget to the group.
     */
    public function addWidget()
    {
        $this->addWidgetWithType('sync', func_get_args());
    }

    /**
     * Add an async widget to the group.
     */
    public function addAsyncWidget()
    {
        $this->addWidgetWithType('async', func_get_args());
    }

    /**
     * Getter for widgets array.
     *
     * @return array
     */
    public function getWidgets()
    {
        return $this->widgets;
    }

    /**
     * Getter for position.
     *
     * @return array
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Display a widget according to its type.
     *
     * @param $widget
     *
     * @return mixed
     */
    protected function displayWidget($widget)
    {
        $factory = (new Wrapper())->appMake($widget['type'] === 'sync' ? 'arrilot.widget' : 'arrilot.async-widget');

        return call_user_func_array([$factory, 'run'], $widget['arguments']);
    }

    /**
     * Reset the position property back to the default.
     * So it does not affect the next widget.
     */
    protected function resetPosition()
    {
        $this->position = 100;
    }

    /**
     * Sort widgets from this group.
     */
    protected function getSortedWidgets()
    {
        return array_values(array_sort($this->widgets, function ($value) {
            return $value['position'];
        }));
    }

    /**
     * Add a widget with a given type to the array.
     *
     * @param string $type
     * @param array  $arguments
     */
    protected function addWidgetWithType($type, array $arguments = [])
    {
        $this->widgets[] = [
            'arguments' => $arguments,
            'type'      => $type,
            'position'  => $this->position,
        ];

        $this->resetPosition();
    }
}
