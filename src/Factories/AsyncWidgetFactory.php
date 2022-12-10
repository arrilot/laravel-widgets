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

        if (!is_object($this->widget)
            || (property_exists($this->widget, 'config') && array_key_exists('disabled',$this->widget->config) && (bool)$this->widget->config['disabled'])) {
            return '';
        }

        $placeholder = call_user_func([$this->widget, 'placeholder']);
        $loader = $this->javascriptFactory->getLoader($this->widget->encryptParams);
        $content = $this->wrapContentInContainer($placeholder.$loader);

        return $this->convertToViewExpression($content);
    }
}
