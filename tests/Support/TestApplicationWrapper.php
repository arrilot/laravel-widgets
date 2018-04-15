<?php

namespace Arrilot\Widgets\Test\Support;

use Arrilot\Widgets\AbstractWidget;
use Arrilot\Widgets\Contracts\ApplicationWrapperContract;
use Arrilot\Widgets\Factories\AsyncWidgetFactory;
use Arrilot\Widgets\Factories\WidgetFactory;
use Illuminate\Container\Container;
use Closure;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class TestApplicationWrapper implements ApplicationWrapperContract
{
    /**
     * Configuration array double.
     *
     * @var array
     */
    public $config = [
        'laravel-widgets.default_namespace'         => 'Arrilot\Widgets\Test\Dummies',
        'laravel-widgets.use_jquery_for_ajax_calls' => true,
    ];

    /**
     * Wrapper around Cache::remember().
     *
     * @param $key
     * @param $minutes
     * @param $tags
     * @param Closure $callback
     *
     * @return mixed
     */
    public function cache($key, $minutes, $tags, Closure $callback)
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
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        throw new InvalidArgumentException("Key {$key} is not defined for testing");
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

        if ($abstract == 'encrypter') {
            return new TestEncrypter();
        }

        if (is_subclass_of($abstract, AbstractWidget::class)) {
            $app = Container::getInstance();

            return $app->make($abstract, $parameters);
        }

        throw new InvalidArgumentException("Binding {$abstract} cannot be resolved while testing");
    }
}
