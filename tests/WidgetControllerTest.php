<?php

namespace Arrilot\Widgets\Test;

use Arrilot\Widgets\Factories\AbstractWidgetFactory;
use Arrilot\Widgets\Factories\WidgetFactory;
use Arrilot\Widgets\Test\Support\TestApplicationWrapper;
use Arrilot\Widgets\Test\Support\TestCase;
use Arrilot\Widgets\WidgetId;
use Arrilot\Widgets\Controllers\WidgetController;
use Illuminate\Http\Request;

class WidgetControllerTest extends TestCase
{
    public function testAjaxRequestDoesNotLeakFactoryStateIntoLaterRendering()
    {
        $previousWidgetId = WidgetId::get();
        $previousSkipWidgetContainer = AbstractWidgetFactory::$skipWidgetContainer;
        $previousAllowOnlyWidgetsWithDisabledEncryption = AbstractWidgetFactory::$allowOnlyWidgetsWithDisabledEncryption;

        try {
            WidgetId::set(40);
            AbstractWidgetFactory::$skipWidgetContainer = false;
            AbstractWidgetFactory::$allowOnlyWidgetsWithDisabledEncryption = false;

            $factory = new WidgetFactory(new TestApplicationWrapper());
            $controller = new WidgetController($factory);
            $request = Request::create('/arrilot/load-widget', 'GET', [
                'id' => 7,
                'name' => 'testUnencryptedWidget',
                'params' => json_encode([]),
                'skip_encryption' => 1,
            ]);

            $this->assertSame('Unencrypted widget output', (string) $controller->showWidget($request));
            $this->assertSame(40, WidgetId::get());
            $this->assertFalse(AbstractWidgetFactory::$skipWidgetContainer);
            $this->assertFalse(AbstractWidgetFactory::$allowOnlyWidgetsWithDisabledEncryption);

            $output = $factory->run('testRepeatableFeed');
            $this->assertStringContainsString('id="arrilot-widget-container-41"', $output);
        } finally {
            WidgetId::set($previousWidgetId);
            AbstractWidgetFactory::$skipWidgetContainer = $previousSkipWidgetContainer;
            AbstractWidgetFactory::$allowOnlyWidgetsWithDisabledEncryption = $previousAllowOnlyWidgetsWithDisabledEncryption;
        }
    }
}
