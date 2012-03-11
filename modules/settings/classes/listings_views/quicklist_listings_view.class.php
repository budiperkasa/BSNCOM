<?php

class quicklistListingsView extends listingsView
{
	public $page_name = LANG_QUICK_LIST_PAGE_TH;
	public $page_key = 'quicklist';

	public function setTypeId($type_id = null)
	{
		$this->type_id = 0;
	}

	public function setDefaults()
	{
		$this->view = 'quicklist';
		$this->format = '10';
	}
}
?>