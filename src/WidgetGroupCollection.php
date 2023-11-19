<?php

namespace Arrilot\Widgets;

use Arrilot\Widgets\Contracts\ApplicationWrapperContract;

class WidgetGroupCollection
{
    /**
     * The application wrapper.
     *
     * @var ApplicationWrapperContract
     */
    protected ApplicationWrapperContract $app;

    /**
     * The array of widget groups.
     *
     * @var array
     */
    protected $groups;

    /**
     * Constructor.
     *
     * @param ApplicationWrapperContract $app
     */
    public function __construct(ApplicationWrapperContract $app)
    {
        $this->app = $app;
    }

    /**
     * Get the widget group object.
     *
     * @param $name
     *
     * @return WidgetGroup
     */
    public function group($name)
    {
        if (isset($this->groups[$name])) {
            return $this->groups[$name];
        }

        $this->groups[$name] = new WidgetGroup($name, $this->app);

        return $this->groups[$name];
    }
}
