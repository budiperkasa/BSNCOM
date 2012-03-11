<?php

class searchListingsView extends listingsView
{
	public $page_name = LANG_SEARCH_PAGE_TH;
	public $page_key = 'search';

	public function setTypeId($type_id = null)
	{
		$this->type_id = 0;
	}

	public function setDefaults()
	{
		$this->view = 'full';
		$this->format = '10';
	}
}
?>