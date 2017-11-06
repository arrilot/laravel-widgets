<?php

namespace Arrilot\Widgets\Factories;

class AsyncWidgetFactory extends AbstractWidgetFactory
{
    /**
     * Run widget without magic method.
     *
     * @return mixed
     */
    public function run()
    {
        $this->instantiateWidget(func_get_args());

        $placeholder = call_user_func([$this->widget, 'placeholder']);
        $loader = $this->javascriptFactory->getLoader($this->widget->encryptParams);
        $content = $this->wrapContentInContainer($placeholder.$loader);

        return $this->convertToViewExpression($content);
    }
}
