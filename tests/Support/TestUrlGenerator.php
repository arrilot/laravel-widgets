<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 12/22/2016
 * Time: 7:09 PM
 */

namespace Arrilot\Widgets\Test\Support;
use Illuminate\Contracts\Routing\UrlGenerator as UrlGeneratorContract;


class TestUrlGenerator implements UrlGeneratorContract
{

    /**
     * Get the current URL for the request.
     *
     * @return string
     */
    public function current()
    {
        // TODO: Implement current() method.
    }

    /**
     * Generate an absolute URL to the given path.
     *
     * @param  string $path
     * @param  mixed $extra
     * @param  bool $secure
     * @return string
     */
    public function to($path, $extra = [], $secure = null)
    {
        return $path;
    }

    /**
     * Generate a secure, absolute URL to the given path.
     *
     * @param  string $path
     * @param  array $parameters
     * @return string
     */
    public function secure($path, $parameters = [])
    {
        // TODO: Implement secure() method.
    }

    /**
     * Generate the URL to an application asset.
     *
     * @param  string $path
     * @param  bool $secure
     * @return string
     */
    public function asset($path, $secure = null)
    {
        // TODO: Implement asset() method.
    }

    /**
     * Get the URL to a named route.
     *
     * @param  string $name
     * @param  mixed $parameters
     * @param  bool $absolute
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function route($name, $parameters = [], $absolute = true)
    {
        // TODO: Implement route() method.
    }

    /**
     * Get the URL to a controller action.
     *
     * @param  string $action
     * @param  mixed $parameters
     * @param  bool $absolute
     * @return string
     */
    public function action($action, $parameters = [], $absolute = true)
    {
        // TODO: Implement action() method.
    }

    /**
     * Set the root controller namespace.
     *
     * @param  string $rootNamespace
     * @return $this
     */
    public function setRootControllerNamespace($rootNamespace)
    {
        // TODO: Implement setRootControllerNamespace() method.
    }
}