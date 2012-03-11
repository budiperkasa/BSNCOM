<?php
include_once(MODULES_PATH . 'users/classes/users_group.class.php');

class users_groupsController extends controller
{
	public function index()
	{
		$this->load->model('users_groups');
	    $users_groups = $this->users_groups->getUsersGroups();

	    $view = $this->load->view();
	    $view->assign('users_groups', $users_groups);
        $view->display('users/admin_users_groups.tpl');
	}
	
	public function save_default()
	{
		$this->load->model('users_groups');
		$users_groups = $this->users_groups->getUsersGroups();
        
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('default_group', LANG_DEFAULT_GROUP, 'integer|required');
            foreach ($users_groups AS $group_item) {
            	$this->form_validation->set_rules('may_register['.$group_item->id.']', LANG_MAY_REGISTER_TH, 'is_checked');
            }
            if ($this->form_validation->run() !== FALSE) {
            	// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
				
				$default_group_id = $this->form_validation->set_value('default_group');
				
				foreach ($users_groups AS $group_item) {
					// Default group is always allowed for registration
					if ($group_item->id == $default_group_id)
						$may_register_bool = 1;
					else 
						$may_register_bool = $this->form_validation->set_value('may_register['.$group_item->id.']');
	            	$this->users_groups->setMayRegisterOfGroup($group_item->id, $may_register_bool);
	            }

            	if ($this->users_groups->setDefaultUsersGroup($default_group_id)) {
            		$this->setSuccess(LANG_DEFAULT_USERS_GROUP_SUCCESS);
            	}
            }
            redirect('admin/users/users_groups/');
        }
	}
	
	/**
	 * validation function
	 *
	 * @param string $name
	 * @return bool
	 */
	public function check_unique_name($name)
	{
		$this->load->model('users_groups');
		
		if ($this->users_groups->is_name($name)) {
			$this->form_validation->set_message('name');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function users_groups_create()
	{
		$this->load->model('users_groups');

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_USERS_GROUP_NAME, 'required|callback_check_unique_name');
            $this->form_validation->set_rules('is_own_page', LANG_USERS_GROUP_OWN_PAGE, 'is_checked');
            $this->form_validation->set_rules('use_seo_name', LANG_USERS_GROUP_USE_SEO, 'is_checked');
            $this->form_validation->set_rules('meta_enabled', LANG_USERS_GROUP_META_ENABLED, 'is_checked');
            $this->form_validation->set_rules('logo_enabled', LANG_USERS_GROUP_LOGO_ENABLED, 'is_checked');
            $required = (isset($_POST['logo_enabled'])) ? 'is_natural_no_zero|required' : '';
			$this->form_validation->set_rules('logo_width', LANG_LOGO_WIDTH, $required);
			$this->form_validation->set_rules('logo_height', LANG_LOGO_HEIGHT, $required);
			$this->form_validation->set_rules('logo_thumbnail_width', LANG_LOGO_WIDTH, $required);
			$this->form_validation->set_rules('logo_thumbnail_height', LANG_LOGO_HEIGHT, $required);

            if ($this->form_validation->run() !== FALSE) {
            	if ($this->users_groups->saveUsersGroup($this->form_validation->result_array())) {
            		// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

            		$this->setSuccess(LANG_USERS_GROUP_CREATE_SUCCESS);
					redirect('admin/users/users_groups/');
				}
            }
            
            $users_group = $this->users_groups->getUsersGroupFromForm($this->form_validation->result_array());
        } else {
            $users_group = $this->users_groups->getNewUsersGroup();
        }
        
        registry::set('breadcrumbs', array(
    		'admin/users/users_groups/' => LANG_USERS_GROUPS_TITLE,
    		LANG_CREATE_USERGROUP,
    	));

        $view = $this->load->view();
        $view->assign('users_group', $users_group);
        $view->display('users/admin_users_group_settings.tpl');
	}
	
	public function users_groups_edit($users_group_id)
    {
		$this->load->model('users_groups');
		$this->users_groups->setUsersGroupId($users_group_id);

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_USERS_GROUP_NAME, 'required|callback_check_unique_name');
            $this->form_validation->set_rules('is_own_page', LANG_USERS_GROUP_OWN_PAGE, 'is_checked');
            $this->form_validation->set_rules('use_seo_name', LANG_USERS_GROUP_USE_SEO, 'is_checked');
            $this->form_validation->set_rules('meta_enabled', LANG_USERS_GROUP_META_ENABLED, 'is_checked');
            $this->form_validation->set_rules('logo_enabled', LANG_USERS_GROUP_LOGO_ENABLED, 'is_checked');
            $required = (isset($_POST['logo_enabled'])) ? 'is_natural_no_zero|required' : '';
			$this->form_validation->set_rules('logo_width', LANG_LOGO_WIDTH, $required);
			$this->form_validation->set_rules('logo_height', LANG_LOGO_HEIGHT, $required);
			$this->form_validation->set_rules('logo_thumbnail_width', LANG_LOGO_WIDTH, $required);
			$this->form_validation->set_rules('logo_thumbnail_height', LANG_LOGO_HEIGHT, $required);

            if ($this->form_validation->run() !== FALSE) {
				if ($this->users_groups->saveUsersGroupById($this->form_validation->result_array())) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

					$this->setSuccess(LANG_USERS_GROUP_SAVE_SUCCESS);
					redirect('admin/users/users_groups/');
				}
			}
            $users_group = $this->users_groups->getUsersGroupFromForm($this->form_validation->result_array());
        } else {
            $users_group = $this->users_groups->getUsersGroupById();
        }
        
        registry::set('breadcrumbs', array(
    		'admin/users/users_groups/' => LANG_USERS_GROUPS_TITLE,
    		LANG_EDIT_USERGROUP,
    	));

        $view = $this->load->view();
        $view->assign('users_group', $users_group);
        $view->display('users/admin_users_group_settings.tpl');
    }
    
    public function users_groups_delete($users_group_id)
    {
        $this->load->model('users_groups');
		$this->users_groups->setUsersGroupId($users_group_id);

        if ($this->input->post('yes')) {
            if ($this->users_groups->deleteUsersGroupById()) {
            	// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

            	$this->setSuccess(LANG_DELETE_USERS_GROUP_SUCCESS);
                redirect('admin/users/users_groups/');
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/users/users_groups/');
        }

        if ( !$users_group = $this->users_groups->getUsersGroupById()) {
            redirect('admin/users/users_groups/');
        }

        registry::set('breadcrumbs', array(
    		'admin/users/users_groups/' => LANG_USERS_GROUPS_TITLE,
    		LANG_DELETE_USERS_GROUP,
    	));

		$view  = $this->load->view();
		$view->assign('options', array($users_group_id => $users_group->name));
        $view->assign('heading', LANG_DELETE_USERS_GROUP);
        $view->assign('question', LANG_DELETE_USERS_GROUP_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
    
    /**
     * Find all access permissions attributes from all active modules and menus
     *
     */
    public function permissions()
    {
		$this->load->model('users_groups');
		
		$users_groups_permissions = $this->users_groups->getUsersGroupsPermissions();

		if ($this->input->post('submit')) {
			$i = 0;
			foreach ($_POST AS $key=>$post_item) {
				$i++;
				if ($key != 'submit') {
					$a = explode(':', $post_item);
					$post_permissions[$i]['group_id'] = $a[0];
					$post_permissions[$i]['function_access'] = $a[1];
				}
			}
			if ($this->users_groups->saveUsersGroupsPermissions($post_permissions, $users_groups_permissions)) {
				// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

				$this->setSuccess(LANG_PERMISSIONS_SAVE_SUCCESS);
				redirect('admin/users/users_groups/permissions/');
			}
		}
		
		$permissions = $this->users_groups->collectPermissionsOfSystem();
		$users_groups = $this->users_groups->getUsersGroups();
		$modules_array = registry::get('modules_array');

		// Link permissions with groups
		foreach ($users_groups_permissions AS $group) {
			$users_groups_ids[] = $group['group_id'];
		}
		$users_groups_ids = array_unique($users_groups_ids);
			
		$view  = $this->load->view();
        $view->assign('users_groups_ids', $users_groups_ids);
        $view->assign('users_groups', $users_groups);
        $view->assign('modules_array', $modules_array);
        $view->assign('permissions', $permissions);
        $view->assign('users_groups_permissions', $users_groups_permissions);
        $view->display('users/admin_users_groups_permissions.tpl');
    }
    
    public function content_permissions()
    {
    	$this->load->model('types', 'types_levels');
    	$types = $this->types->getTypesLevels();
		
		$this->load->model('users_groups', 'users');
		$users_groups = $this->users_groups->getUsersGroups();
		$content_permissions = $this->users_groups->getContentPermissions('levels');
		
		if ($this->input->post('submit')) {
			$i = 0;
			foreach ($_POST AS $key=>$post_item) {
				$i++;
				if ($key != 'submit') {
					$a = explode(':', $post_item);
					$post_permissions[$i]['group_id'] = $a[0];
					$post_permissions[$i]['object_id'] = $a[1];
				}
			}
			if ($this->users_groups->saveContentUsersGroupsPermissions('levels', $post_permissions, $content_permissions)) {
				// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

				$this->setSuccess(LANG_PERMISSIONS_SAVE_SUCCESS);
				redirect('admin/users/content/permissions/');
			}
		}

        $view  = $this->load->view();
        $view->assign('types', $types);
        $view->assign('users_groups', $users_groups);
        $view->assign('content_permissions', $content_permissions);
        $view->display('users/admin_content_permissions_manage.tpl');
    }
}
?>