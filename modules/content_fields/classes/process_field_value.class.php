<?php

/**
 * parent of all fields classes (checkboxes, datetime, datetimerange, ......),
 * works with field and its value,
 * processes common behaviour for all classes - select, save, update, delete, get value from form,
 * these methods may be overwritten in children
 *
 */
abstract class processFieldValue
{
	public $field;

    public function __construct($field_array = null)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

        $this->field = new field;
        //if (!$field_array) {
        	$this->field->id = $field_array['id'];
        	$this->field->name = $field_array['name'];
        	$this->field->frontend_name = $field_array['frontend_name'];
        	$this->field->seo_name = $field_array['seo_name'];
        	$this->field->type = $field_array['type'];
        	$this->field->type_name = $field_array['type_name'];
        	$this->field->configuration_page = $field_array['configuration_page'];
        	$this->field->search_configuration_page = $field_array['search_configuration_page'];
        	$this->field->description = $field_array['description'];
        	$this->field->required = $field_array['required'];

        	if ($field_array) {
		    	$CI->content_fields->setFieldId($this->field->id);
        	}
		        $this->field->options = $CI->content_fields->selectFullFieldConfiguration($this->field->type);
		        $this->field->search_options = $CI->content_fields->selectSearchFieldConfiguration($this->field->type);
        //}
    }

	public function select($custom_group_name, $object_id)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

    	$CI->content_fields->db->select();
    	$CI->content_fields->db->from('content_fields_type_' . $this->field->type . '_data');
    	$CI->content_fields->db->where('field_id', $this->field->id);
    	$CI->content_fields->db->where('custom_name', $custom_group_name);
    	$CI->content_fields->db->where('object_id', $object_id);
    	$query = $CI->content_fields->db->get();
    	
    	if ($row = $query->row_array()) {
    		$this->field->value = $row['field_value'];
    		$this->field->field_value_id = $row['id'];
    	}
    }

    public function save($custom_group_name, $object_id, $form_result)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

    	$CI->content_fields->db->set('field_id', $this->field->id);
    	$CI->content_fields->db->set('custom_name', $custom_group_name);
    	$CI->content_fields->db->set('object_id', $object_id);
    	$CI->content_fields->db->set('field_value', $form_result['field_' . $this->field->seo_name]);

    	if ($CI->content_fields->db->insert('content_fields_type_' . $this->field->type . '_data')) {
        	$system_settings = registry::get('system_settings');
        	/*if ($system_settings['language_areas_enabled']) {
        		translations::saveTranslations(array('content_fields_type_' . $this->field->type . '_data', 'field_value', $contentFieldsModel->db->insertId()));
        	}*/
        	return true;
        }
    }

    public function update($custom_group_name, $object_id, $form_result)
    {
    	if ($this->field->field_value_id == 'new') {
    		return $this->save($custom_group_name, $object_id, $form_result);
    	} else {
    		$CI = &get_instance();

	    	$CI->content_fields->db->set('field_value', $form_result['field_' . $this->field->seo_name]);
	    	$CI->content_fields->db->where('field_id', $this->field->id);
	    	$CI->content_fields->db->where('custom_name', $custom_group_name);
	    	$CI->content_fields->db->where('object_id', $object_id);
	    	return $CI->content_fields->db->update('content_fields_type_' . $this->field->type . '_data');
    	}
    }
    
    public function delete($custom_group_name, $object_id)
    {
    	if (!is_null($this->field->field_value_id)) {
    		$CI = &get_instance();

	    	$CI->content_fields->db->where('field_id', $this->field->id);
	    	$CI->content_fields->db->where('custom_name', $custom_group_name);
	    	$CI->content_fields->db->where('object_id', $object_id);
	    	return $CI->content_fields->db->delete('content_fields_type_' . $this->field->type . '_data');
    	} else
    		return true;
    }
    
    /**
     * Return field's value after the form hasn't pass validation
     *
     * @param object $form
     */
    public function getValueFromForm($form_result)
    {
    	$this->field->value = $form_result['field_' . $this->field->seo_name];
    }
}
?>