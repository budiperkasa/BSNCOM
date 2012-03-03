<?php
include_once(MODULES_PATH . "content_fields/classes/process_field_value.class.php");

class field
{
	public $id;
	public $name = '';
	public $frontend_name = '';
	public $seo_name = '';
	public $type;
	public $type_name = '';
	public $configuration_page = 1;
	public $search_configuration_page = 1;
	public $description = '';
	public $required = 0;
	public $v_index_page = 0;
	public $v_types_page = 0;
	public $v_categories_page = 0;
	
	public $options = array();
	public $value = '';
	public $field_value_id = 'new';
	
	public function __construct($type = null)
	{
		$this->id = 'new';
		if (!empty($type))
			$this->type = $type;
	}
}
?>