<?php

namespace Arrilot\Widgets\Misc;

use Illuminate\View\Expression;

trait ViewExpressionTrait
{
    /**
     * Convert a given html to View Expression object that was introduced in Laravel 5.1.
     *
     * @param string $html
     * @return \Illuminate\View\Expression|string
     */
    protected function convertToViewExpression($html)
    {
        if (interface_exists('Illuminate\Contracts\Support\Htmlable') && class_exists('Illuminate\View\Expression'))
        {
            return new Expression($html);
        }

        return $html;
    }
}