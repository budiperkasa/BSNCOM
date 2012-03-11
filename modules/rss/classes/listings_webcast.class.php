<?php

class listingsWebCast
{
	private $_channel_title;
	private $_channel_link;
	private $_channel_description;
	private $_channel_image;
	private $_users_content_path;

	private $_listings = array();
	
	public function __construct($channel_title, $channel_link, $channel_description, $channel_image, $users_content_path)
	{
		//$this->_channel_title = htmlentities($channel_title);
		$this->_channel_title = htmlspecialchars($channel_title);
		//$this->_channel_link = htmlentities($channel_link);
		$this->_channel_link = htmlspecialchars($channel_link);
		//$this->_channel_description = htmlentities($channel_description);
		$this->_channel_description = htmlspecialchars($channel_description);
		//$this->_channel_image = htmlentities($channel_image);
		$this->_channel_image = htmlspecialchars($channel_image);
		$this->_users_content_path = $users_content_path . '/';
	}
	
	public function getListingsArray($listings)
	{
		$this->_listings = $listings;
	}
	
	public function run()
	{
		$doc = new DOMDocument('1.0', 'UTF-8');
		$rss = $doc->createElement('rss');
		$doc->appendChild($rss);
		$rss->setAttribute('version', '2.0');

		$channel = $doc->createElement('channel');
		$rss->appendChild($channel);
		
		$title = $doc->createElement('title', $this->_channel_title);
		$link = $doc->createElement('link', $this->_channel_link);
		$description = $doc->createElement('description', $this->_channel_description);
		$channel->appendChild($title);
		$channel->appendChild($link);
		$channel->appendChild($description);
		
		$image = $doc->createElement('image');
		$channel->appendChild($image);
		$image_url = $doc->createElement('url', $this->_channel_image);
		$image->appendChild($image_url);
		$image_title = $doc->createElement('title', $this->_channel_title);
		$image->appendChild($image_title);
		$image_link = $doc->createElement('link', $this->_channel_link);
		$image->appendChild($image_link);

		foreach ($this->_listings AS $listing) {
			$item = $doc->createElement('item');
			$channel->appendChild($item);

			$element = $item->appendChild($doc->createElement('title'));
			$element->appendChild($doc->createTextNode($listing->title()));
			
			$listing_link = $doc->createElement('link', site_url($listing->url()));
			$item->appendChild($listing_link);

			$element = $item->appendChild($doc->createElement('description'));
			$element->appendChild($doc->createTextNode($listing->listing_description_teaser()));

			$listing_date = $doc->createElement('pubDate', date('r', strtotime($listing->creation_date)));
			$item->appendChild($listing_date);


			// --------------------------------------------------------------------------------------------
			// Add enclosure/image element
			// --------------------------------------------------------------------------------------------
			if ($listing->logo_file) {
				$logo_file_path = $this->_users_content_path . 'users_images/logos/' . $listing->logo_file;
				$info = getimagesize($logo_file_path);
				$mime_type = image_type_to_mime_type($info[2]);
				
				$enclosure = $doc->createElement('enclosure');
				
				$url_attr = $doc->createAttribute('url');
				$attr_content = $doc->createTextNode($logo_file_path);
				$url_attr->appendChild($attr_content); 
				$enclosure->appendChild($url_attr);
				
				$type_attr = $doc->createAttribute('type');
				$attr_content = $doc->createTextNode($mime_type);
				$type_attr->appendChild($attr_content); 
				$enclosure->appendChild($type_attr);
				
				$item->appendChild($enclosure);
			}
			// --------------------------------------------------------------------------------------------
		}

		echo $doc->saveXML();
	}
}
?>