<?php

namespace Arrilot\Widgets\Test\Support;

use Arrilot\Widgets\WidgetId;
use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function tearDown()
    {
        WidgetId::reset();
    }

    public function ajaxUrl($widgetName, $widgetParams = [], $id = 1)
    {
        $url = Config::get('laravel-widgets.url_prefix', 'lazyapi') . '/' . Config::get('laravel-widgets.url_name', 'load') . '?';
        return $url . http_build_query([
            'id'     => $id,
            'name'   => $widgetName,
            'params' => json_encode($widgetParams),
        ]);
    }
}
