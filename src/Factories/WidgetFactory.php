<?php

namespace Arrilot\Widgets\Factories;

use Arrilot\Widgets\WidgetGroup;
use Illuminate\Support\Facades\Cache;

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
        $args = func_get_args();
        $this->instantiateWidget($args);

        if ($cacheTime = $this->getCacheTime()) {
            $content = $this->app->cache($this->widget->cacheKey($args), $cacheTime, function() {
                return $this->getContent();
            });
        } else {
            $content = $this->getContent();
        }

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
     * Get widget cache time or false if it's not meant to be cached.
     *
     * @return bool|float|int
     */
    protected function getCacheTime()
    {
        return isset($this->widget) && $this->widget->cacheTime ? $this->widget->cacheTime : false;
    }

    /**
     * Make call and get return widget content.
     *
     * @return mixed
     */
    protected function getContent()
    {
        $content = $this->app->call([$this->widget, 'run'], $this->widgetParams);

        return is_object($content) ? $content->__toString() : $content;
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
