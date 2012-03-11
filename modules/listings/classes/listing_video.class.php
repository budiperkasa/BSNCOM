<?php
include_once(MODULES_PATH . 'listings/classes/listing_video_attached.class.php');
include_once(MODULES_PATH . 'listings/classes/listing_video_uploaded.class.php');

abstract class listingVideo
{
    public $id = 'new';
    public $listing_id;
    public $title = '';
    public $video_code = '';
    public $status = 'success';
    public $error_code = '';
    public $creation_date;

    public function setVideoFromArray($array)
    {
    	foreach ($array AS $key=>$value) {
    		$this->$key = $value;
    	}
    }
}
?>