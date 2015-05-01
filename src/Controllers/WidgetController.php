<?php

namespace Arrilot\Widgets\Controllers;

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
    public function showAsyncWidget(Request $request)
    {
        $factory      = app()->make('arrilot.widget');
        $widgetName   = $request->get('name', '');
        $widgetParams = unserialize($request->get('params', ''));

        return call_user_func_array([$factory, $widgetName], $widgetParams);
    }
}
