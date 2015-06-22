<?php

namespace Arrilot\Widgets\Misc;

use Closure;

class LaravelApplicationWrapper
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
    public function call($method, $params = [])
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
    public function make($binding)
    {
        return app()->make($binding);
    }

    /**
     * Wrapper around Cache::remember().
     *
     * @param $key
     * @param $minutes
     * @param callable $callback
     *
     * @return mixed
     */
    public function cache($key, $minutes, Closure $callback)
    {
        return app()->make('cache')->remember($key, $minutes, $callback);
    }
}
