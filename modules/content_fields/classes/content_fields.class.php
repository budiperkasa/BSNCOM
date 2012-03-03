<?php
include_once(MODULES_PATH . 'content_fields/classes/field.class.php');
include_once(MODULES_PATH . 'content_fields/classes/process_field_value.class.php');

$fields_files_path = MODULES_PATH . 'content_fields/classes/fields_classes/';
$map = directory_map($fields_files_path);
foreach ($map AS $file) {
	include_once($fields_files_path . $file);
}

function not_only_spaces($string)
{
	// remove tabs and spaces from the string
    $string = str_replace('	', '', str_replace(' ', '', $string));
    //var_dump($string);
    return strlen($string);
}

/**
 * controls content fields operations connected with custom groups,
 * such as input or output of content fields
 *
 */
class contentFields
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
     * the name of the content fields group
     *
     * @var string
     * @var int
     */
    private $custom_group_name = null;
    
    /**
     * ID of the object, whome contains content fields
     *
     * @var int
     */
    private $object_id = null;

    public function __construct($custom_group_name, $custom_group_id, $object_id = null, $page = 0)
    {
    	$CI = &get_instance();

    	$this->custom_group_name = $custom_group_name;
    	$this->object_id = $object_id;
    	
    	$cache_id = 'content_fields_' . $custom_group_name . '_' . $custom_group_id /*. '_' .$object_id */ . '_' . $page;
    	if (!$cache = $CI->cache->load($cache_id)) {
    		$CI->load->model('content_fields_groups', 'content_fields');
    		$CI->load->model('content_fields', 'content_fields');

	    	// Select fields of the group
	    	$this->fields_array = $CI->content_fields_groups->getFieldsByGroupName($custom_group_name, $custom_group_id, $page);

	    	// Create an object of each field
	    	foreach ($this->fields_array AS $field) {
	    		$field_type = $field['type'];
		    	$class = $field_type . 'Class';
		    	$object = new $class($field);
		    	$this->fields_objects[$field['seo_name']] = $object;
		    }
		    $CI->cache->save(array($this->fields_array, $this->fields_objects), $cache_id, array('content_fields' /*, 'search_fields'*/));
    	} else {
			list($this->fields_array, $this->fields_objects) = $cache;
		}
    }
    
    public function setObjectId($id)
    {
    	$this->object_id = $id;
    }

    /**
     * renders input of all fields step-by-step
     *
     * @return html
     */
    public function inputMode()
    {
    	$output = '';
    	
    	if (property_exists($this, 'fields_objects')) {
	    	foreach ($this->fields_objects AS $object) {
	    		$output .= $object->renderDataEntry();
	    	}
    	}

    	return $output;
    }
    
    /**
     * renders all fields html step-by-step
     *
     * @param string $view_type
     * @return html
     */
    public function outputMode($view_type = 'full')
    {
    	$output = '';
    	
    	if (property_exists($this, 'fields_objects')) {
	        foreach ($this->fields_objects AS $object) {
	    		$output .= $object->renderDataOutput($view_type);
	    	}
    	}
    	
    	return $output;
    }
    
    // --------------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------------

    /**
     * renders input html of only field by its seo name
     *
     * @param string $seo_name
     * @return html
     */ 
    public function inputField($seo_name)
    {
    	if (isset($this->fields_objects[$seo_name]))
    		return $this->fields_objects[$seo_name]->renderDataEntry();
    }
    
    /**
     * renders output html of only field by its seo name and view type
     *
     * @param string $seo_name
     * @param string $view_type
     * @return html
     */
    public function outputField($seo_name, $view_type = 'full')
    {
    	if (isset($this->fields_objects[$seo_name]))
    		return $this->fields_objects[$seo_name]->renderDataOutput($view_type);
    }
    
    /**
     * return all fields objects in array
     *
     * @return array
     */
    public function getFieldsObjects()
    {
    	return $this->fields_objects;
    }
    
    /**
     * set new fields objects array
     *
     * @param array $fields_objects
     */
    public function setFieldsObjects($fields_objects)
    {
    	$this->fields_objects = $fields_objects;
    }
    
	/**
     * returns field class'es instance
     *
     * @param string $seo_name
     * @return field obj
     */
    public function getField($seo_name)
    {
    	return $this->fields_objects[$seo_name];
    }
    
    /**
     * returns field as object
     *
     * @param string $seo_name
     * @return field obj
     */
    public function getFieldObject($seo_name)
    {
    	return $this->fields_objects[$seo_name]->field;
    }
    
    /**
     * returns field value as is
     * For example:
     * for checkboxes - array(array('field_value')) - ids of checked options
     * for datetime - timestamp
     * for datetime range - array('from_field_value', 'to_field_value') - timestamps
     * for email - just a string with email
     * for price - array('field_currency', 'field_value') - field_currency as id of option
     * richtext - just a string with text
     * select - id of selected option
     * text - just a string with text
     * varchar - just a string with text
     * website - string with url
     *
     * @param string $seo_name
     * @return mixed
     */
    public function getFieldValue($seo_name)
    {
    	return $this->fields_objects[$seo_name]->field->value;
    }
    
    /**
     * returns field as userfriendly string,
     * some field types may receive option, such as glue for array items of value
     *
     * @param string $seo_name
     * @return string
     */
    public function getFieldValueAsString($seo_name, $option = null)
    {
    	if (method_exists($this->fields_objects[$seo_name], 'getValueAsString'))
	    	if ($option)
	    		return $this->fields_objects[$seo_name]->getValueAsString($option);
	    	else 
	    		return $this->fields_objects[$seo_name]->getValueAsString();
	    else 
	    	return false;
    }
    
    /**
     * if any of content fields have input
     *
     * @return bool
     */
    public function isAnyValue()
    {
    	foreach ($this->fields_objects AS $object) {
    		if ($object->getValueAsString())
    			return true;
    	}
    	return false;
    }

    /**
     * removes one field from fields array
     *
     * @param string $seo_name
     */
    public function unsetField($seo_name)
    {
    	unset($this->fields_objects[$seo_name]);
    	foreach ($this->fields_array AS $key=>$field) {
		    if ($field['seo_name'] == $seo_name)
		    	unset($this->fields_array[$key]);
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
    
    
    // --------------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------------
    // --------------------------------------------------------------------------------------------
    public function validate($form_validation)
    {
    	if (property_exists($this, 'fields_objects')) {
	    	foreach ($this->fields_objects AS $object) {
	    		$object->validate($form_validation);
	    	}
    	}
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
    
    public function select()
    {
    	if (property_exists($this, 'fields_objects')) {
	    	if (!empty($this->object_id)) {
		    	foreach ($this->fields_objects AS $key=>$object) {
			    	$object->select($this->custom_group_name, $this->object_id);
			    }
	    	}
    	}
    }
    
    public function save($form_result)
    {
    	if (property_exists($this, 'fields_objects')) {
	    	foreach ($this->fields_objects AS $key=>$object) {
	    		$object->save($this->custom_group_name, $this->object_id, $form_result);
	    	}
    	}
    	return true;
    }
    
    public function update($form_result)
    {
    	if (property_exists($this, 'fields_objects')) {
	    	foreach ($this->fields_objects AS $key=>$object) {
	    		$object->update($this->custom_group_name, $this->object_id, $form_result);
	    	}
    	}
    	return true;
    }

    public function delete()
    {
    	if (property_exists($this, 'fields_objects')) {
	    	foreach ($this->fields_objects AS $key=>$object) {
	    		$object->delete($this->custom_group_name, $this->object_id);
	    	}
    	}
    	return true;
    }
}
?>