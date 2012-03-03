<?php
include_once(MODULES_PATH . 'content_fields/classes/field.class.php');
include_once(MODULES_PATH . 'content_fields/classes/process_field_value.class.php');

$fields_files_path = MODULES_PATH . 'content_fields/classes/fields_classes/';
$map = directory_map($fields_files_path);
foreach ($map AS $file) {
	include_once($fields_files_path . $file);
}

/**
 * controls content fields operations connected with custom groups,
 * such as input or output of content fields
 *
 */
class searchContentFields
{
	/**
	 * contains content fields array
	 *
	 * @var array
	 */
    private $fields_array = array();
    
    /**
	 * contains content fields instances, links to the object in custom group
	 *
	 * @var array
	 */
    private $fields_objects = array();
    
    /**
     * name and ID of the content fields group
     *
     * @var string
     * @var int
     */
    private $custom_group_name = null;
    private $custom_group_id = null;
    
    public function __construct($custom_group_name, $custom_group_id = 0, $mode = 'quick')
    {
    	$CI = &get_instance();

    	$this->custom_group_name = $custom_group_name;
    	$this->custom_group_id = $custom_group_id;

    	$cache_id = 'search_fields_' . $custom_group_name . '_' . $custom_group_id . '_' . $mode;
    	if (!$cache = $CI->cache->load($cache_id)) {
	    	$CI->load->model('content_fields', 'content_fields');
	    	$CI->load->model('search_fields', 'search');
	
	    	// Select fields of the group
	    	$this->fields_array = $CI->search_fields->getFieldsByGroupName($custom_group_name, $custom_group_id, $mode);

	    	// Create an object of each field
	    	foreach ($this->fields_array AS $field) {
	    		$field_type = $field['type'];
			    $class = $field_type . 'Class';
		    	$object = new $class($field);
		    	$this->fields_objects[$field['seo_name']] = $object;
		    }
			$CI->cache->save(array($this->fields_array, $this->fields_objects), $cache_id, array('search_fields', 'content_fields'));
		} else {
			list($this->fields_array, $this->fields_objects) = $cache;
		}
    }

    public function inputMode($args)
    {
    	$output = '';
    	
    	if (property_exists($this, 'fields_objects')) {
	    	foreach ($this->fields_objects AS $object) {
	    		$output .= $object->renderSearch($args);
	    	}
    	}

    	return $output;
    }
    
    /**
     * renders html of only field by its seo name and view type
     *
     * @param string $seo_name
     * @return html
     */
    public function inputField($seo_name, $args)
    {
    	if (isset($this->fields_objects[$seo_name]))
    		return $this->fields_objects[$seo_name]->renderSearch($args);
    }

    
    public function validateSearch($fields_group_name, $args, $use_advanced, &$search_url_rebuild)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');
	    $CI->load->model('search_fields', 'search');

    	$search_sql_array = array();
    	
    	// Validate advanced search fields
    	if ($use_advanced) {
    		$advanced_fields = $CI->search_fields->getFieldsByGroupName($this->custom_group_name, $this->custom_group_id, 'advanced');

    		// Create an object of each field
	    	//$fields_classes_path = MODULES_PATH . 'content_fields/classes/fields_classes/';
	    	foreach ($advanced_fields AS $field) {
	    		//include_once($fields_classes_path . $field['type'] . '.class.php');
		    	$class = $field['type'] . 'Class';
		    	$object = new $class($field);
		    	if ($sql = $object->validateSearch($fields_group_name, $args, $search_url_rebuild)) {
	    			$connector = key($sql);
	    			// Execute subqueries preliminarily - and if nothing found return id=0
			    	if ($result = $CI->db->query(array_shift($sql))->result_array()) {
				    	$ids = array();
				    	foreach ($result AS $row) {
				    		$ids[] = $row['object_id'];
				    	}
				    	$search_sql_array[] = array($connector => $ids);
			    	} else {
			    		$search_sql_array[] = array($connector => 0);
			    	}
	    		}
		    }
    	}

    	if (property_exists($this, 'fields_objects')) {
	    	foreach ($this->fields_objects AS $object) {
	    		if ($sql = $object->validateSearch($fields_group_name, $args, $search_url_rebuild)) {
	    			$connector = key($sql);
	    			// Execute subqueries preliminarily - and if nothing found return id=0
			    	if ($result = $CI->db->query(array_shift($sql))->result_array()) {
				    	$ids = array();
				    	foreach ($result AS $row) {
				    		$ids[] = $row['object_id'];
				    	}
				    	$search_sql_array[] = array($connector => $ids);
			    	} else {
			    		$search_sql_array[] = array($connector => 0);
			    	}
	    		}
	    	}
    	}

    	return $search_sql_array;
    }

    public function validate($form_validation)
    {
    	if (property_exists($this, 'fields_objects')) {
	    	foreach ($this->fields_objects AS $object) {
	    		$object->validate($form_validation);
	    	}
    	}
    }
    
    /**
     * returns the count of fields
     *
     * @return int
     */
    public function fieldsCount()
    {
    	return count($this->fields_array);
    }
    
    /**
     * fields objects of the page return its values from form if it was not valid
     *
     * @param object $form
     */
    public function getValuesFromForm($form_result)
    {
    	if (property_exists($this, 'fields_objects')) {
	    	foreach ($this->fields_objects AS $key=>$object) {
	    		//$this->fields_objects[$key]->field->value = $form->result['field_' . $object->field->seo_name];
	    		$this->fields_objects[$key]->getValueFromForm($form_result);
	    	}
    	}
    }
}
?>