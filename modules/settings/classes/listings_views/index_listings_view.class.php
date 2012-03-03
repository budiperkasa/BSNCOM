<?php

class indexListingsView extends listingsView
{
	public $page_name = LANG_INDEX_PAGE_TH;
	public $page_key = 'index';

	public function setTypeId($type_id = null)
	{
		if ($type_id)
			$this->type_id = $type_id;
	}
	
	public function setDefaults()
	{
		$this->view = 'semitable';
		$this->format = '3*1';
	}
}
?>