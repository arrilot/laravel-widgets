<?php

namespace Arrilot\Widgets\Contracts;

use Closure;

interface ApplicationWrapperContract
{
    /**
     * Wrapper around Cache::remember().
     *
     * @param $key
     * @param $minutes
     * @param callable $callback
     *
     * @return mixed
     */
    public function cache($key, $minutes, Closure $callback);

    /**
     * Wrapper around app()->call().
     *
     * @param $method
     * @param array $params
     *
     * @return mixed
     */
    public function call($method, $params = []);

    /**
     * Get the specified configuration value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function config($key, $default = null);

    /**
     * Wrapper around app()->getNamespace().
     *
     * @return string
     */
    public function getNamespace();

    /**
     * Wrapper around app()->make().
     *
     * @param string $abstract
     * @param array  $parameters
     *
     * @return mixed
     */
    public function make($abstract, array $parameters = []);
}
