<?php

class searchController extends controller
{
	public function index($mode)
    {
    	// Global search group
    	if ($mode == 'quick') {
    		$group_id = 1;
    	} elseif ($mode == 'advanced') {
    		$group_id = 2;
    	}
    	
    	$this->load->model('search_fields');
    	$this->search_fields->setGroupId($group_id);
    	
    	$group = $this->search_fields->getFieldsGroupById();
    	$fields_of_group = $this->search_fields->getFieldsOfGroupById();
    	$free_fields = $this->search_fields->selectFieldsNotInGroup($fields_of_group);

        $view  = $this->load->view();
        $view->assign('fields_of_group', $fields_of_group);
        $view->assign('free_fields', $free_fields);
        $view->assign('group_name', $group['name']);
        $view->assign('group_id', $group_id);
        $view->display('search/admin_manage_fields_in_group.tpl');
    }
    
    public function choose_type()
	{
		$this->load->model('search_fields');
    	$groups = $this->search_fields->selectLocalSearchGroups();

    	$view = $this->load->view();
    	$view->assign('groups', $groups);
        $view->display('search/admin_manage_fields_groups.tpl');
	}
	
	public function by_type($group_id)
    {
    	$this->load->model('search_fields');
    	$this->search_fields->setGroupId($group_id);
    	
    	$group = $this->search_fields->getFieldsGroupById();
    	$fields_of_group = $this->search_fields->getFieldsOfGroupById();
    	$free_fields = $this->search_fields->selectFieldsNotInGroup($fields_of_group);
    	
    	registry::set('breadcrumbs', array(
    		'admin/search/by_type/' => LANG_MANAGE_SEARCH_FIELDS_GROUPS_BY_TYPE,
    		$group['name'],
    	));

        $view  = $this->load->view();
        $view->assign('fields_of_group', $fields_of_group);
        $view->assign('free_fields', $free_fields);
        $view->assign('group_name', $group['name']);
        $view->assign('group_id', $group_id);
        $view->display('search/admin_manage_fields_in_group.tpl');
    }
    
    public function save_fields_of_group($group_id)
    {
	   	$serialized_list = $this->input->post('serialized_list');
	    $this->load->model('search_fields');
	   	$this->search_fields->setGroupId($group_id);

	    $this->search_fields->saveFieldsListOfGroup($serialized_list);
	    // Clean cache
		$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('content_fields'));
    }
}
?>