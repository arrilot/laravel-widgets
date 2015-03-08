<?php namespace Arrilot\Widgets\Misc;

class Wrapper {

    public function csrf_token()
    {
        return csrf_token();
    }
}
