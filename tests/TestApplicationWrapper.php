<?php

namespace Arrilot\Widgets\Test;

use Arrilot\Widgets\Contracts\ApplicationWrapperContract;
use Arrilot\Widgets\Factories\AsyncWidgetFactory;
use Arrilot\Widgets\Factories\WidgetFactory;
use Closure;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class TestApplicationWrapper implements ApplicationWrapperContract
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
    public function cache($key, $minutes, Closure $callback)
    {
        return 'Cached output. Key: '.$key.', minutes: '.$minutes;
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
        return call_user_func_array($method, $params);
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
        if ($key == 'laravel-widgets.default_namespace') {
            return 'Arrilot\Widgets\Test\Dummies';
        }

        if ($key == 'laravel-widgets.disable_jquery') {
            return false;
        }

        throw new InvalidArgumentException("Key {$key} is not defined for testing");
    }

    /**
     * Wrapper around csrf_token().
     *
     * @return string
     */
    public function csrf_token()
    {
        return 'token_stub';
    }

    /**
     * Wrapper around app()->getNamespace().
     *
     * @return string
     */
    public function getNamespace()
    {
        return 'App\\';
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
        if ($abstract == 'arrilot.widget') {
            return new WidgetFactory($this);
        }

        if ($abstract == 'arrilot.async-widget') {
            return new AsyncWidgetFactory($this);
        }

        throw new InvalidArgumentException("Binding {$abstract} cannot be resolved while testing");
    }
}
