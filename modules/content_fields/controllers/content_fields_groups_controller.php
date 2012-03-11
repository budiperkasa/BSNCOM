<?php

class content_fields_groupsController extends controller
{
	public function index()
	{
		$view = $this->load->view();
		$this->load->model('content_fields_groups');
    	$groups = $this->content_fields_groups->selectAllGroups();

    	$view->assign('groups', $groups);
        $view->display('content_fields/admin_manage_fields_groups.tpl');
	}

    public function manage_fields($group_id)
    {
    	$this->load->model('content_fields_groups');
    	$this->content_fields_groups->setFieldsGroupId($group_id);

        $group = $this->content_fields_groups->getFieldsGroupById();
        $fields_of_group = $this->content_fields_groups->getFieldsOfGroupById();
    	$free_fields = $this->content_fields_groups->selectFieldsNotInGroup($fields_of_group);
    	
    	registry::set('breadcrumbs', array(
    		'admin/fields/groups/' => LANG_VIEW_CONTENT_FIELDS_GROUP,
    		LANG_MANAGE_CONTENT_FIELDS_GROUP . ' "' . $group['name'] . '"',
    	));

        $view  = $this->load->view();
        $view->assign('fields_of_group', $fields_of_group);
        $view->assign('free_fields', $free_fields);
        $view->assign('group_name', $group['name']);
        $view->assign('group_id', $group_id);
        $view->display('content_fields/admin_manage_fields_in_group.tpl');
    }
    
    public function save_fields_of_group($group_id)
    {
    	$serialized_list = $this->input->post('serialized_list');

	   	$this->load->model('content_fields_groups');
	   	$this->content_fields_groups->setFieldsGroupId($group_id);

	   	$this->content_fields_groups->saveFieldsListOfGroup($serialized_list);
	   	// Clean cache
		$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('content_fields'));
    }
}
?>