<?php namespace Arrilot\Widgets\Controllers;

use App\Http\Controllers\Controller;
use Arrilot\Widgets\WidgetFactory;
use Illuminate\Support\Facades\Input;

class WidgetController extends Controller {

    public function showAsyncWidget(WidgetFactory $factory)
    {
        $widget = Input::get('widget');

        return $factory->{$widget['name']}($widget['config']);
    }

}
