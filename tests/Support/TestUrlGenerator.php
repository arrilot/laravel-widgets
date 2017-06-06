<?php

namespace Arrilot\Widgets\Test\Support;


class TestUrlGenerator
{
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
}