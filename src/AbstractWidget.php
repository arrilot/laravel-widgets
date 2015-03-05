<?php namespace Arrilot\Widgets;

abstract class AbstractWidget {

    private static $incrementingId;

	public function __construct($config)
	{
		foreach ($config as $property => $value)
		{
			if(property_exists($this, $property))
			{
				$this->$property = $value;
			}
		}
	}

    public static function incrementId()
    {
        self::$incrementingId++;
    }

	abstract public function run();
}