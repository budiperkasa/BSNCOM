<?php

class listingImage
{
	public $id = 'new';
	public $listing_id;
	public $title;
	public $file;
	public $creation_date;

	public function setImageFromArray($array)
    {
    	foreach ($array AS $key=>$value) {
    		$this->$key = $value;
    	}
    }
}
?>