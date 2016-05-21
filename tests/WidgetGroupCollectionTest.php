<?php

namespace Arrilot\Widgets\Test;

use Arrilot\Widgets\Test\Support\TestApplicationWrapper;
use Arrilot\Widgets\Test\Support\TestCase;
use Arrilot\Widgets\WidgetGroup;
use Arrilot\Widgets\WidgetGroupCollection;

class WidgetGroupCollectionTest extends TestCase
{
    /**
     * @var WidgetGroupCollection
     */
    protected $collection;

    public function setUp()
    {
        $this->collection = new WidgetGroupCollection(new TestApplicationWrapper());
    }

    public function testItGrantsAccessToWidgetGroup()
    {
        $groupObject = $this->collection->group('sidebar');

        $expectedObject = new WidgetGroup('sidebar', new TestApplicationWrapper());

        $this->assertEquals($expectedObject, $groupObject);
    }
}
