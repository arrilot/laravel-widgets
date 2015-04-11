<?php namespace Arrilot\Widgets;

use Arrilot\Widgets\Misc\Wrapper;

class WidgetGroup {

    protected $name;

    protected $widgets = [];

    protected $position = 100;


    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Displays all widgets from this group.
     */
    public function display()
    {
        $output = '';
        foreach ($this->getSortedWidgets() as $widget)
        {
            $output .=  $this->displayWidget($widget);
        }

        return $output;
    }

    /**
     * Set widget position.
     *
     * @param int $position
     * @return $this
     */
    public function position($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Add widget to group.
     */
    public function addWidget()
    {
        $this->widgets[] = [
            'arguments' => func_get_args(),
            'type' => 'sync',
            'position' => $this->position,
        ];

        $this->resetPosition();
    }

    /**
     * Add async widget to group.
     */
    public function addAsyncWidget()
    {
        $this->widgets[] = [
            'arguments' => func_get_args(),
            'type' => 'async',
            'position' => $this->position,
        ];

        $this->resetPosition();
    }

    /**
     * Getter for widgets.
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
     * Displays widget according to its type.
     *
     * @param $widget
     * @return mixed
     */
    protected function displayWidget($widget)
    {
        $factory = (new Wrapper)->appMake($widget['type'] === 'sync' ? 'arrilot.widget' : 'arrilot.async-widget');

        return call_user_func_array([$factory, 'run'], $widget['arguments']);
    }

    /**
     * Reset position var back to default so it does not affect next widget.
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
        return array_values(array_sort($this->widgets, function($value)
        {
            return $value['position'];
        }));
    }

}