<?php

class categoriesListingsView extends listingsView
{
	public $page_name = LANG_CATEGORIES_PAGE_TH;
	public $page_key = 'categories';

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