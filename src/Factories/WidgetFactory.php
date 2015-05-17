<?php

namespace Arrilot\Widgets\Factories;

use Arrilot\Widgets\WidgetGroup;

class WidgetFactory extends AbstractWidgetFactory
{
    /**
     * The array of widget groups.
     *
     * @var array
     */
    protected $groups;

    /**
     * Run widget without magic method.
     *
     * @return mixed
     */
    public function run()
    {
        $this->instantiateWidget(func_get_args());

        $content = $this->wrapper->appCall([$this->widget, 'run'], $this->widgetParams);

        if ($timeout = $this->getReloadTimeout()) {
            $content .= $this->javascriptFactory->getReloader($timeout);
        }

        return $this->wrapContentInContainer($content);
    }

    /**
     * Get widget reload timeout or false if it's not reloadable.
     *
     * @return bool|float|int
     */
    protected function getReloadTimeout()
    {
        return isset($this->widget) && $this->widget->reloadTimeout ? $this->widget->reloadTimeout : false;
    }

    /**
     * Get the widget group object.
     *
     * @param $name
     *
     * @return mixed
     */
    public function group($name)
    {
        if ($this->groups[$name]) {
            return $this->groups[$name];
        }

        $this->groups[$name] = new WidgetGroup($name);

        return $this->groups[$name];
    }
}
