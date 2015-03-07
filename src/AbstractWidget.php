<?php namespace Arrilot\Widgets;

abstract class AbstractWidget {

    /**
     * Id for async widget.
     *
     * @var int
     */
    public static $incrementingId = 0;

    /**
     * Constructor.
     *
     * @param $config
     */
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

    /**
     * Placeholder for async widget.
     *
     * @return string
     */
    public function placeholder() { return ''; }

    /**
     * Resets the incrementing id to 0.
     *
     * @return string
     */
    public static function resetId() { self::$incrementingId = 0; }

    /**
     * You can treat this method just like a controller action.
     * Return a view or anything else you want to display.
     */
    abstract public function run();

}