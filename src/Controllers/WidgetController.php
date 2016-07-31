<?php

namespace Arrilot\Widgets\Controllers;

use Arrilot\Widgets\Factories\AbstractWidgetFactory;
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
        $this->prepareGlobals($request);

        $factory = app()->make('arrilot.widget');
        $widgetName = $request->input('name', '');
        $widgetParams = $factory->decryptWidgetParams($request->input('params', ''));

        return call_user_func_array([$factory, $widgetName], $widgetParams);
    }

    /**
     * Set some specials variables to modify the workflow of the widget factory.
     *
     * @param Request $request
     */
    protected function prepareGlobals(Request $request)
    {
        WidgetId::set($request->input('id', 1) - 1);
        AbstractWidgetFactory::$skipWidgetContainer = true;
    }
}
