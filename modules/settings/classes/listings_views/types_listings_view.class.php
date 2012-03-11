<?php

class typesListingsView extends listingsView
{
	public $page_name = LANG_TYPES_PAGE_TH;
	public $page_key = 'types';

	public function setTypeId($type_id = null)
	{
		if ($type_id)
			$this->type_id = $type_id;
	}

	public function setDefaults()
	{
		$this->view = 'full';
		$this->format = '10';
	}
}
?>