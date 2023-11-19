<?php

namespace Arrilot\Widgets\Controllers;

use Arrilot\Widgets\Factories\AbstractWidgetFactory;
use Arrilot\Widgets\Factories\WidgetFactory;
use Arrilot\Widgets\WidgetId;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class WidgetController extends BaseController
{
    private WidgetFactory $factory;

    public function __construct(WidgetFactory $factory)
    {
        $this->factory = $factory;
    }

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

        $widgetName = $request->input('name', '');

        $widgetParams = $request->input('skip_encryption', '')
            ? $request->input('params', '')
            : $this->factory->decryptWidgetParams($request->input('params', ''));

        $decodedParams = json_decode($widgetParams, true);
        
        $params = $decodedParams ?: [];
        array_unshift($params, $widgetName);

        return call_user_func_array([$this->factory, 'run'], $params);
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
        if ($request->input('skip_encryption', '')) {
            AbstractWidgetFactory::$allowOnlyWidgetsWithDisabledEncryption = true;
        }
    }
}
