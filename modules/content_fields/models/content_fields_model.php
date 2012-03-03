<?php
include_once(MODULES_PATH . 'content_fields/classes/field.class.php');

class content_fieldsModel extends Model
{
	private $_field_id;
	private $field_types;
	
	/**
	 * point existed field id
	 *
	 * @param int $field_id
	 */
	public function setFieldId($field_id)
	{
		$this->_field_id = $field_id;
	}
	
	/**
	 * point existed search group id
	 *
	 * @param int $group_id
	 */
	public function setSearchGroupId($group_id)
	{
		$this->_search_group_id = $group_id;
	}
	
	/**
	 * select fields rows from DB
	 *
	 * @return array
	 */
	public function selectAllFields()
	{
		$this->db->select();
		$this->db->from('content_fields');
		$this->db->order_by('id');
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * returns the instance of field object
	 *
	 * @return field object
	 */
	public function getFieldById()
	{
		return $this->buildFieldObj($this->getFieldArrayById());
	}
	
	/**
	 * select one row of the proper content field
	 *
	 * @return array
	 */
	public function getFieldArrayById()
	{
		$this->db->select();
		$this->db->from('content_fields');
		$this->db->where('id', $this->_field_id);
		$query = $this->db->get();

		return $query->row_array();
	}
	
	/**
	 * is there content field with such name in the DB?
	 *
	 * @param string $name
	 * @return bool
	 */
	public function is_field_name($name)
	{
		$this->db->select();
		$this->db->from('content_fields');
		$this->db->where('name', $name);
		if (!is_null($this->_field_id)) {
			$this->db->where('id !=', $this->_field_id);
		}
		$query = $this->db->get();

		return $query->num_rows();
	}
	
	/**
	 * is there content field with such seo name in the DB?
	 *
	 * @param string $seo_name
	 * @return bool
	 */
	public function is_seo_field_name($seo_name)
	{
		$this->db->select();
		$this->db->from('content_fields');
		$this->db->where('seo_name', $seo_name);
		if (!is_null($this->_field_id)) {
			$this->db->where('id !=', $this->_field_id);
		}
		$query = $this->db->get();

		return $query->num_rows();
	}
	
	/**
	 * saves new field from field object
	 *
	 * @param field $field
	 * @return bool
	 */
	public function saveField($field)
	{
        $this->db->insert('content_fields', array(
        	'name' => $field->name, 
        	'frontend_name' => $field->frontend_name, 
        	'seo_name' => $field->seo_name,
        	'type' => $field->type,
        	'type_name' => $field->type_name,
        	'configuration_page' => $field->configuration_page,
        	'search_configuration_page' => $field->search_configuration_page,
        	'description' => $field->description,
        	'required' => $field->required,
        	'v_index_page' => $field->v_index_page,
        	'v_types_page' => $field->v_types_page,
        	'v_categories_page' => $field->v_categories_page,
        	'v_search_page' => $field->v_search_page
        ));

        $system_settings = registry::get('system_settings');
        if (@$system_settings['language_areas_enabled']) {
        	translations::saveTranslations(array('content_fields', 'name', $this->db->insert_id()));
        	translations::saveTranslations(array('content_fields', 'description', $this->db->insert_id()));
        }
        return true;
	}
	
	/**
	 * updates existed field from field object
	 *
	 * @param field $field
	 * @return bool
	 */
	public function saveFieldById($field)
	{
		$this->db->where('id', $this->_field_id);
		$this->db->update('content_fields', array(
        	'name' => $field->name, 
        	'frontend_name' => $field->frontend_name, 
        	'seo_name' => $field->seo_name,
        	'description' => $field->description,
        	'required' => $field->required,
        	'v_index_page' => $field->v_index_page,
        	'v_types_page' => $field->v_types_page,
        	'v_categories_page' => $field->v_categories_page,
        	'v_search_page' => $field->v_search_page,
        ));
        return true;
	}
	
	/**
	 * deletes content field from DB
	 *
	 * @return bool
	 */
	public function deleteFieldById()
	{
		$field_array = $this->getFieldArrayById();

		// delete field records from groups
        $this->db->delete('content_fields_to_groups', array('field_id' => $this->_field_id));

        // delete field's configuration record
		$this->db->select('configuration_page');
		$this->db->from('content_fields');
		$this->db->where('id', $this->_field_id);
		$query = $this->db->get();
		// if configuration is enabled
		$content_field = $query->row_array('configuration_page');
		if ($content_field['configuration_page']) {
			$this->db->delete('content_fields_type_' . $field_array['type'], array('field_id' => $this->_field_id));
		}

		// delete field's data records
		$this->db->delete('content_fields_type_' . $field_array['type'] . '_data', array('field_id' => $this->_field_id));

		// delete field record
        return $this->db->delete('content_fields', array('id' => $this->_field_id));
	}
	
	/**
	 * returns array of field types, extracted from classes directory of content fields module
	 *
	 * @return array of field types instances
	 */
	public function getAllFieldTypes()
	{
		$field_types = array();
		$fields_classes_path = MODULES_PATH . 'content_fields/classes/fields_classes/';
		$fields_dir = directory_map($fields_classes_path);
		foreach ($fields_dir AS $field_class_file) {
			if (strpos($field_class_file, ".class.php") !== false) {
				$type = str_replace(".class.php", "", $field_class_file);
				include_once($fields_classes_path . $field_class_file);
				$field_type_name = $type . 'Class';
				$field_type_instance = new $field_type_name;
				$field_types[$type] = $field_type_instance;
			}
		}
		$this->field_types = $field_types;
		return $field_types;
	}
	
	/**
	 * return the instance of new field class
	 *
	 * @return field object
	 */
	public function getNewField()
	{
		$field = new field(reset($this->field_types));
		return $field;
	}
	
	/**
	 * builds field object from entered validation result and from array of field instances
	 *
	 * @param array $form
	 * @return field object
	 */
	public function buildFieldObj($form)
	{
		// We need all instances of each field type
		if ($this->field_types == null) {
			$this->getAllFieldTypes();
		}
		
		$field = new field;
		// from entered validation result
		if (isset($form['id']))
			$field->id = $form['id'];
		$field->name = $form['name'];
		$field->frontend_name = $form['frontend_name'];
		$field->seo_name = $form['seo_name'];
		if (isset($form['type']))
			$field->type = $form['type'];
		$field->description = $form['description'];
		$field->required = $form['required'];
		$field->v_index_page = $form['v_index_page'];
		$field->v_types_page = $form['v_types_page'];
		$field->v_categories_page = $form['v_categories_page'];
		$field->v_search_page = $form['v_search_page'];

		if (isset($form['type'])) {
			// from array of field instances
			$field_instance = $this->field_types[$form['type']];
			$field->type_name = $field_instance->name;
			$field->configuration_page = ($field_instance->configuration) ? 1 : 0;
			$field->search_configuration_page = ($field_instance->search_configuration) ? 1 : 0;
		}
		return $field;
	}

	/**
	 * Call configuration view directly from content field type class
	 *
	 */
	public function configureField()
	{
		$field = $this->getFieldArrayById();
		$fields_classes_path = MODULES_PATH . 'content_fields/classes/fields_classes/';
		include_once($fields_classes_path . $field['type'] . '.class.php');
		
		registry::set('breadcrumbs', array(
    		'admin/fields/' => LANG_MANAGE_CONTENT_FIELDS,
    		'admin/fields/configure/' . $field['id'] => LANG_CONFIGURE_FIELD_1 . ' "' . $field['name'] . '"',
    	));
		
		$field_type = $field['type'] . 'Class';
		$field_type_instance = new $field_type($field);
		$field_type_instance->configurationPage();
	}
	
	/**
	 * selects configuration options of current field from the DB
	 *
	 * @param string $field_type
	 * @return array of options
	 */
	public function selectFullFieldConfiguration($field_type)
	{
		if ($this->db->table_exists('content_fields_type_' . $field_type)) {
			$this->db->select();
			$this->db->from('content_fields_type_' . $field_type);
			$this->db->where('field_id', $this->_field_id);
			$query = $this->db->get();
	
			$options = $query->result_array();
			

			// There aren't any options in DB of this content field
			if (empty($options)) {
				// We need all instances of each field type
				if ($this->field_types == null) {
					$this->getAllFieldTypes();
				}
				
				// Get default options from field type class
				$field_type_instance = $this->field_types[$field_type];
				$options = $field_type_instance->getDefaultOptions();
			}

			// Order by 'order num' field - only if it is existed
			//
			// this thing was made just for common result_array() interface of DB, 
			// instead of order_by('order_num'), 
			// because $options var may contain only one row without order_num column into DB table
			if (isset($options[0]['order_num'])) {
				$tmp_array = array();
		    	foreach ($options AS $key=>$item) {
		    		$tmp_array[] = $options[$key]['order_num'];
		    	}
		    	sort($tmp_array);
		    	$res_array = array();
		    	foreach ($tmp_array AS $tmp_weight) {
		    		foreach ($options AS $key=>$item) {
		    			if ($item['order_num'] == $tmp_weight) {
		    				$res_array[$key] = $item;
		    			}
		    		}
		    	}
			} else {
				$res_array = $options;
			}
	    	return $res_array;
	    } else {
			return false;
		}
	}
	
	/**
	 * selects search options of current field from the DB
	 *
	 * @param string $field_type
	 * @return array of options
	 */
	public function selectSearchFieldConfiguration($field_type)
	{
		if ($this->db->table_exists('search_fields_type_' . $field_type)) {
			$this->db->select();
			$this->db->from('search_fields_type_' . $field_type);
			$this->db->where('field_id', $this->_field_id);
			$query = $this->db->get();
	
			$options = $query->result_array();
			
			// There aren't any options in DB of this content field
			if (empty($options)) {
				// We need all instances of each field type
				if ($this->field_types == null) {
					$this->getAllFieldTypes();
				}
				
				// Get default options from field type class
				$field_type_instance = $this->field_types[$field_type];
				$options = $field_type_instance->getDefaultSearchOptions($field_type);
			}
			return  $options;
		} else {
			return false;
		}
	}
	
	/**
	 * copy field with full options structure
	 *
	 * @return int - created field ID
	 */
	public function copyField()
	{
		$fields_array = $this->selectAllFields();
		
		foreach ($fields_array AS $field_array) {
			if ($field_array['id'] == $this->_field_id) {
				$original_field = $field_array;
				foreach ($original_field AS $key=>$value) {
					if (strpos($key, 'name') !== false && $key != 'type_name') {
						if ($key == 'seo_name')
							$original_field[$key] = 'copy_' . $value;
						else
							$original_field[$key] = 'copy ' . $value;
					}
				}
			}
		}

		foreach ($fields_array AS $field_array) {
			foreach ($field_array AS $key=>$value) {
				if ($key != 'id') {
					if (strpos($key, 'name') !== false && $original_field[$key] == $value) {
						foreach ($original_field AS $_key=>$_value) {
							if (strpos($_key, 'name') !== false && $key != 'type_name') {
								if ($key == 'seo_name')
									$original_field[$_key] = 'copy_' . $_value;
								else
									$original_field[$_key] = 'copy ' . $_value;
							}
						}
					}
				}
			}
		}

		foreach ($original_field AS $key=>$value) {
			if ($key != 'id') {
				$this->db->set($key, $value);
			}
		}
		$this->db->insert('content_fields');

		$created_field_id = $this->db->insert_id();

		if ($original_field['configuration_page']) {
			$this->db->select();
			$this->db->from('content_fields_type_' . $original_field['type']);
			$this->db->where('field_id', $this->_field_id);
			$query = $this->db->get();
			
			$options = $query->result_array();
			foreach ($options AS $option) {
				$this->db->set('field_id', $created_field_id);
				foreach ($option AS $key=>$value) {
					if ($key != 'id' && $key != 'field_id') {
						$this->db->set('`' . $key . '`', $value);
					}
				}
				$this->db->insert('content_fields_type_' . $original_field['type']);
			}
		}

		return $created_field_id;
	}
	
	/**
	 * Call configuration view directly from content field type class
	 *
	 */
	public function configureFieldSearch()
	{
		$field = $this->getFieldArrayById();
		$fields_classes_path = MODULES_PATH . 'content_fields/classes/fields_classes/';
		include_once($fields_classes_path . $field['type'] . '.class.php');
		
		registry::set('breadcrumbs', array(
    		'admin/fields/' => LANG_MANAGE_CONTENT_FIELDS,
    		'admin/fields/configure_search/' . $field['id'] => LANG_CONFIGURE_SEARCH_FIELD_1 . ' "' . $field['name'] . '"',
    	));
		
		$field_type = $field['type'] . 'Class';
		$field_type_instance = new $field_type($field);
		$field_type_instance->searchConfigurationPage();
	}
	
	/**
	 * returns url of the website in content_fields_type_website_data table by its row ID
	 *
	 * @param int $field_value_id
	 * @return url
	 */
	public function getWebsiteUrlById($field_value_id)
	{
		$this->db->select('field_value');
		$this->db->from('content_fields_type_website_data');
		$this->db->where('id', $field_value_id);
		$query = $this->db->get();
		
		$row = $query->row_array();
		return $row['field_value'];
	}
}
?>