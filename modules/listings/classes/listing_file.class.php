<?php
class listingFile
{
	public $id = 'new';
	public $listing_id;
	public $title = '';
	public $file = '';
	public $file_format;
	public $creation_date;

	public function setFileFromArray($array)
	{
		foreach ($array AS $key=>$value) {
    		$this->$key = $value;
    	}
	}
}
?>