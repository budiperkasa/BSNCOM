<?php
class usersModule
{
	public $title = "Users";
	public $version = "0.1";
	public $description = "Users management module.";
	public $type = "core";
	public $permissions = array('Manage users', 'Edit self profile', 'Control permissions');
	
	public $lang_files = "users.php";
	
	public function routes()
	{
		$route['admin/users/search/(.*)'] = array(
			'title' => LANG_SEARCH_USERS_TITLE,
			'action' => 'search',
			'access' => 'Manage users',
		);
		
		$route['admin/users/create/(.*)'] = array(
			'title' => LANG_USER_CRETE_TITLE,
			'action' => 'create',
			'access' => 'Manage users',
		);

		$route['admin/users/profile/:num/'] = array(
			'title' => LANG_USER_PROFILE_TITLE,
			'action' => 'profile',
			'access' => 'Manage users',
		);

		$route['admin/users/block/'] = array(
			'action' => 'block',
			'access' => 'Manage users',
		);
		
		$route['admin/users/view/:num/'] = array(
			'title' => LANG_USER_VIEW_TITLE,
			'action' => 'view',
			'access' => 'Manage users',
		);
		
		$route['admin/users/delete/:num/'] = array(
			'title' => LANG_USER_DELETE_TITLE,
			'action' => 'delete',
			'access' => 'Manage users',
		);
		
		$route['admin/users/change_group/:num/'] = array(
			'title' => LANG_CHANGE_USER_GROUP_TITLE,
			'action' => 'change_group',
			'access' => 'Manage users',
		);
		
		$route['admin/users/change_status/:num/'] = array(
			'title' => LANG_CHANGE_USER_STATUS_TITLE,
			'action' => 'change_status',
			'access' => 'Manage users',
		);
		
		$route['admin/users/my_profile/'] = array(
			'title' => LANG_EDIT_MY_PROFILE_TITLE,
			'action' => 'my_profile',
			'access' => 'Edit self profile',
		);
		
		$route['admin/users/ajax_autocomplete_request/'] = array(
			'action' => 'ajax_autocomplete_request',
			'access' => 'Manage users',
		);
		
		$route['admin/users/get_logo/:num'] = array(
			'action' => 'get_logo',
			'access' => array('Manage users', 'Edit self profile'),
		);
		

		/* ----------------------------------------------------------------------
		*  Manage users groups
		*/
		$route['admin/users/users_groups/'] = array(
			'title' => LANG_USERS_GROUPS_TITLE,
			'controller' => 'users_groups',
			'access' => 'Manage users',
		);
		
		$route['admin/users/users_groups/save_default/'] = array(
			'controller' => 'users_groups',
			'action' => 'save_default',
			'access' => 'Manage users',
		);
		
		$route['admin/users/users_groups/create/'] = array(
			'title' => LANG_CREATE_USERS_GROUP_TITLE,
			'controller' => 'users_groups',
			'action' => 'users_groups_create',
			'access' => 'Control permissions',
		);
		
		$route['admin/users/users_groups/edit/:num/'] = array(
			'title' => LANG_EDIT_USERS_GROUP_TITLE,
			'controller' => 'users_groups',
			'action' => 'users_groups_edit',
			'access' => 'Manage users',
		);
		
		$route['admin/users/users_groups/delete/:num/'] = array(
			'title' => LANG_DELETE_USERS_GROUP_TITLE,
			'controller' => 'users_groups',
			'action' => 'users_groups_delete',
			'access' => 'Manage users',
		);

		$route['admin/users/users_groups/permissions/'] = array(
			'title' => LANG_USERS_GROUPS_PERMISSIONS_TITLE,
			'controller' => 'users_groups',
			'action' => 'permissions',
			'access' => 'Control permissions',
		);
		
		// Controlls users content permissions
		$route['admin/users/content/permissions/'] = array(
			'title' => LANG_CONTENT_PERMISSIONS_TITLE,
			'controller' => 'users_groups',
			'action' => 'content_permissions',
			'access' => 'Control permissions',
		);

		return $route;
	}
	
	public function menu()
	{
		$menu[LANG_EDIT_MY_PROFILE_MENU] = array(
			'weight' => 2,
			'url' => 'admin/users/my_profile/',
			'icon' => 'user',
			'access' => 'Edit self profile',
		);
		
		$menu[LANG_USERS_MENU] = array(
			'weight' => 54,
			'children' => array(
				LANG_SEARCH_USERS => array(
					'url' => 'admin/users/search/',
					'sinonims' => array(
						array('admin', 'users', 'search', '%+'), 
						array('admin', 'users', 'create', '%+'), 
						array('admin', 'users', 'profile', '%'), 
						array('admin', 'users', 'delete', '%'), 
						array('admin', 'users', 'delete'), 
						array('admin', 'users', 'view', '%'), 
						array('admin', 'users', 'change_group', '%'), 
						array('admin', 'users', 'change_status', '%')
					),
					'access' => 'Manage users',
				),
				LANG_USERS_GROUPS_MENU => array(
					'url' => 'admin/users/users_groups/',
					'sinonims' => array(array('admin', 'users', 'users_groups', 'create'), array('admin', 'users', 'users_groups', 'edit', '%'), array('admin', 'users', 'users_groups', 'delete', '%')),
					'access' => 'Manage users',
				),
				LANG_USERS_GROUPS_PERMISSIONS_MENU => array(
					'url' => 'admin/users/users_groups/permissions/',
					'access' => 'Control permissions',
				),
				LANG_CONTENT_PERMISSIONS_MENU => array(
					'url' => 'admin/users/content/permissions/',
					'access' => 'Control permissions',
				),
			),
		);

		return $menu;
	}
	
	public function hooks()
	{
		$hook['buildMyProfileMenuItem'] = array(
			'weight' => 2,
		);
	
		return $hook;
	}
}
?>