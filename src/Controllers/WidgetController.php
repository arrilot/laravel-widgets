<?php

namespace Arrilot\Widgets\Controllers;

use Arrilot\Widgets\WidgetId;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class WidgetController extends BaseController
{
    /**
     * The action to show widget output via ajax.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function showWidget(Request $request)
    {
        $factory = app()->make('arrilot.widget');
        $widgetName = $request->get('name', '');
        $widgetParams = unserialize($request->get('params', ''));

        WidgetId::set($request->get('id', 1) - 1);

        return call_user_func_array([$factory, $widgetName], $widgetParams);
    }
}
