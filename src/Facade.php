<?php namespace Arrilot\Widget;

class Facade extends \Illuminate\Support\Facades\Facade
{
	protected static function getFacadeAccessor()
	{
		return 'arrilot_widget';
	}
}
