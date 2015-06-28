<?php

namespace Arrilot\Widgets;

abstract class AbstractWidget
{
    /**
     * The number of seconds before each reload.
     * False means no reload at all.
     *
     * @var int|float|bool
     */
    public $reloadTimeout = false;

    /**
     * The number of minutes before cache expires.
     * False means no caching at all.
     *
     * @var int|float|bool
     */
    public $cacheTime = false;

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
     * You can customize it by overwriting this method.
     *
     * @return string
     */
    public function placeholder()
    {
        return '';
    }

    /**
     * Async and reloadable widgets are wrapped in container.
     * You can customize it by overwriting this method.
     *
     * @return array
     */
    public function container()
    {
        return [
            'element'       => 'div',
            'attributes'    => 'style="display:inline" class="arrilot-widget-container"',
        ];
    }

    /**
     * Cache key that is used if caching is enabled.
     *
     * @param $params
     *
     * @return string
     */
    public function cacheKey(array $params = [])
    {
        return 'arrilot.widgets.'.serialize($params);
    }
}
