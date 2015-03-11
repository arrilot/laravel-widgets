<?php namespace Arrilot\Widgets\Misc;

use Illuminate\Support\Facades\App;

class Wrapper {

    public function csrf_token()
    {
        return csrf_token();
    }

    public function appCall($method, $params = [])
    {
        return App::call($method, $params);
    }
}
