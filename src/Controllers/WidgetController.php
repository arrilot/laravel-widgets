<?php namespace Arrilot\Widgets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class WidgetController extends Controller {

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
