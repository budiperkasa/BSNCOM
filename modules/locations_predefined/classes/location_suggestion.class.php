<?php

class locationSuggestion
{
	public $label;
	public $value;
	
	public function __construct($suggestion, $location_id)
	{
		$this->label = $suggestion;
		$this->value = $location_id;
	}
}
?>