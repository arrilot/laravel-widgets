<?php namespace Arrilot\Widgets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class WidgetController extends Controller {

    public function showAsyncWidget()
    {
        $factory = app()->make('arrilot.widget');
        $widget = Input::get('widget');

        return $factory->{$widget['name']}($widget['config']);
    }

}
