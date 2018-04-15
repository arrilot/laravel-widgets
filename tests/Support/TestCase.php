<?php

namespace Arrilot\Widgets\Test\Support;

use Arrilot\Widgets\WidgetId;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function tearDown()
    {
        WidgetId::reset();
    }

    public function ajaxUrl($widgetName, $widgetParams = [], $id = 1)
    {
        return '/arrilot/load-widget?'.http_build_query([
            'id'     => $id,
            'name'   => $widgetName,
            'params' => json_encode($widgetParams),
        ]);
    }
}
