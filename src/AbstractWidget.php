<?php namespace Arrilot\Widgets;

abstract class AbstractWidget {

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

	abstract public function run();
}