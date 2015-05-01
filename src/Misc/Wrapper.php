<?php

namespace Arrilot\Widgets\Misc;

class Wrapper
{
    /**
     * Wrapper around csrf_token().
     *
     * @return string
     */
    public function csrf_token()
    {
        return csrf_token();
    }

    /**
     * Wrapper around app()->call().
     *
     * @param $method
     * @param array $params
     *
     * @return mixed
     */
    public function appCall($method, $params = [])
    {
        return app()->call($method, $params);
    }

    /**
     * Wrapper around app()->make().
     *
     * @param $binding
     *
     * @return mixed
     */
    public function appMake($binding)
    {
        return app()->make($binding);
    }
}
