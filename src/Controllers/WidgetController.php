<?php namespace Arrilot\Widgets\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class WidgetController extends BaseController {

    /**
     * Show widget content action.
     *
     * @param Request $request
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
