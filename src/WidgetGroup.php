<?php namespace Arrilot\Widgets;

class WidgetGroup {

	protected $name;

	protected $widgets = [];

	protected $position = 100;


	public function __construct($name)
	{
		$this->name = $name;
	}

	/**
	 * Displays all widgets from this group.
	 */
	public function display()
	{
		foreach ($this->getSortedWidgets() as $widget)
		{
			echo $this->displayWidget($widget);
		}
	}

	/**
	 * Set widget position.
	 *
	 * @param int $position
	 * @return $this
	 */
	public function position($position)
	{
		$this->position = $position;

		return $this;
	}

	/**
	 * Add widget to group.
	 */
	public function addWidget()
	{
		$this->widgets[] = [
			'arguments' => func_get_args(),
			'type' => 'sync',
			'position' => $this->position,
		];

		$this->resetPosition();
	}

	/**
	 * Add async widget to group.
	 */
	public function addAsyncWidget()
	{
		$this->widgets[] = [
			'arguments' => func_get_args(),
			'type' => 'async',
			'position' => $this->position,
		];

		$this->resetPosition();
	}

	/**
	 * Displays widget according to its type.
	 *
	 * @param $widget
	 * @return mixed
	 */
	protected function displayWidget($widget)
	{
		$factory = app()->make($widget['type'] === 'sync' ? 'arrilot.widget' : 'arrilot.async-widget');

		return call_user_func_array([$factory, 'run'], $widget['arguments']);
	}


	/**
	 * Reset position var back to default so it does not affect next widget.
	 */
	protected function resetPosition()
	{
		$this->position = 100;
	}


	/**
	 * Sort widgets from this group.
	 */
	protected function getSortedWidgets()
	{
		return array_values(array_sort($this->widgets, function($value)
		{
			return $value['position'];
		}));
	}
}