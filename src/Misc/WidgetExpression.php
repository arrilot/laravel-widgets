<?php

namespace Arrilot\Widgets\Misc;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\View\Expression;

/**
 * Represents an widget to the view.
 */
class WidgetExpression extends Expression
{
    protected $widget;

    /**
     * {@inheritdoc}
     * @param AbstractWidget $widget instance of the widget this expression
     *                               represents
     */
    public function __construct($html, AbstractWidget $widget)
    {
        $this->widget = $widget;
        parent::__construct($html);

    }

    /**
     * Get the instance of the widget this expression represents.
     *
     * @return AbstractWidget
     */
    public function getWidget()
    {
        return $this->widget;
    }

    /**
     * Call a method directly on the widget instance.
     *
     * @param string $method method name
     * @param array  $params
     * @return mixed
     */
    public function __call($method, array $params = [])
    {
        if (is_callable($this->widget, $method)) {
            return call_user_func_array([$this->widget, $method], $params);
        }
        throw new InvalidArgumentException(
            sprintf(
                '"%s" does not have a method of "%s"',
                get_class($this->widget),
                $method
            )
        );
    }
}
