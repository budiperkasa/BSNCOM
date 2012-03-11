<?php

class packageClass
{
	public $id;
	public $name;
	public $items;
	public $levels;
	
	public function __construct()
	{
		$this->id = 'new';
		$this->name = '';
		$this->items = array();
		$this->levels = array();
	}
	
	public function setPackageFromArray($row)
	{
		if (isset($row['id']))
			$this->id = $row['id'];
		$this->name = $row['name'];
	}
	
	public function setItem($level_obj, $listings_count)
	{
		$this->items[$level_obj->id] = $listings_count;
		$this->levels[$level_obj->id] = $level_obj;
	}
}
?>