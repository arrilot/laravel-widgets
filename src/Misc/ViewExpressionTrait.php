<?php

namespace Arrilot\Widgets\Misc;

use Illuminate\Support\HtmlString;
use Illuminate\View\Expression;

trait ViewExpressionTrait
{
    /**
     * Convert a given html to HtmlString object that was introduced in Laravel 5.1.
     *
     * @param string $html
     *
     * @return \Illuminate\Support\HtmlString|string
     */
    protected function convertToViewExpression($html)
    {
        if (class_exists('Illuminate\Support\HtmlString')) {
            return new HtmlString($html);
        } elseif (class_exists('Illuminate\View\Expression')) {
            return new Expression($html);
        }

        return $html;
    }
}
