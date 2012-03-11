<?php

class userSuggestions
{
	public $query;
	public $suggestions;
	
	public function __construct($query, $suggestions)
	{
		$this->query = $query;
		$this->suggestions = $suggestions;
	}
}
?>