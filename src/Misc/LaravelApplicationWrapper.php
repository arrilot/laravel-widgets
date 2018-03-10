<?php

namespace Arrilot\Widgets\Misc;

use Arrilot\Widgets\Contracts\ApplicationWrapperContract;
use Closure;
use Illuminate\Container\Container;

class LaravelApplicationWrapper implements ApplicationWrapperContract
{
    /**
     * Laravel application instance.
     */
    protected $app;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->app = Container::getInstance();
    }

    /**
     * Wrapper around Cache::remember().
     *
     * @param $key
     * @param $minutes
     * @param $tags
     * @param callable $callback
     *
     * @return mixed
     */
    public function cache($key, $minutes, $tags, Closure $callback)
    {
        $cache = $this->app->make('cache');

        if (method_exists($cache->getStore(), 'tags')) {
            $cache = $cache->tags($tags);
        }

        return $cache->remember($key, $minutes, $callback);
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
        return $this->app->call($method, $params);
    }

    /**
     * Get the specified configuration value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function config($key, $default = null)
    {
        return $this->app->make('config')->get($key, $default);
    }

    /**
     * Wrapper around app()->getNamespace().
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->app->getNamespace();
    }

    /**
     * Wrapper around app()->make().
     *
     * @param string $abstract
     * @param array  $parameters
     *
     * @return mixed
     */
    public function make($abstract, array $parameters = [])
    {
        return $this->app->make($abstract, $parameters);
    }
}
