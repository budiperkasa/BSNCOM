<?php
class content_fieldsModule
{
	public $title = "Content fields";
	public $version = "0.2";
	public $description = "Content fields provide additional information in listings, users profiles, content pages. Types of fields: string, textarea, richtext, date-time, date-time range, select box, checkboxes group, email, website, price.";
	public $type = "core";
	public $permissions = array('Manage content fields');
	
	public $lang_files = "content_fields.php";
	
	public function routes()
	{
		$route['admin/fields/'] = array(
			'title' => LANG_CONTENT_FIELDS_TITLE,
			'access' => 'Manage content fields',
		);
		
		$route['admin/fields/create/'] = array(
			'title' => LANG_CREATE_CONTENT_FIELD_TITLE,
			'action' => 'create',
			'access' => 'Manage content fields',
		);
		
		$route['admin/fields/edit/(\d+)/'] = array(
			'title' => LANG_EDIT_CONTENT_FIELD_TITLE,
			'action' => 'edit',
			'access' => 'Manage content fields',
		);
		
		$route['admin/fields/delete/(\d+)/'] = array(
			'title' => LANG_DELETE_CONTENT_FIELD_TITLE,
			'action' => 'delete',
			'access' => 'Manage content fields',
		);
		
		$route['admin/fields/configure/(\d+)/(.*)'] = array(
			'title' => LANG_CONFIGURE_CONTENT_FIELD,
			'action' => 'configure',
			'access' => 'Manage content fields',
		);
		
		$route['admin/fields/configure_search/:num/(.*)'] = array(
			'title' => LANG_CONFIGURE_SEARCH_FIELD,
			'action' => 'configure_search',
			'access' => 'Manage content fields',
		);

		$route['admin/fields/copy/(\d+)/'] = array(
			'action' => 'copy',
			'access' => 'Manage content fields',
		);
		
		//--------------------------------------------------------------------
		
		$route['admin/fields/groups/'] = array(
			'title' => LANG_CONTENT_FIELDS_GROUPS_TITLE,
			'controller' => 'content_fields_groups',
			'access' => 'Manage content fields',
		);

		$route['admin/fields/groups/manage_fields/:num'] = array(
			'title' => LANG_MANAGE_CONTENT_FIELDS_IN_GROUP_TITLE,
			'action' => 'manage_fields',
			'controller' => 'content_fields_groups',
			'access' => 'Manage content fields',
		);
		
		$route['admin/fields/groups/manage_fields/:num/save'] = array(
			'action' => 'save_fields_of_group',
			'controller' => 'content_fields_groups',
			'access' => 'Manage content fields',
		);

		$route['redirect/([a-zA-Z 0-9~%.:_\-\/]*)'] = array(
			'action' => 'website_redirect',
		);

		return $route;
	}
	
	public function menu()
	{
		$menu[LANG_CONTENT_FIELDS_MENU] = array(
			'weight' => 60,
			'access' => 'Manage content fields',
			'children' => array(
				LANG_MANAGE_FIELDS_MENU => array(
					'weight' => 1,
					'url' => 'admin/fields/',
					'sinonims' => array(
						array('admin', 'fields', 'create'), 
						array('admin', 'fields', 'edit', '%'), 
						array('admin', 'fields', 'delete', '%'), 
						array('admin', 'fields', 'configure', '%'),
						array('admin', 'fields', 'configure_search', '%+')
					),
				),
				LANG_MANAGE_FIELDS_GROUPS_MENU => array(
					'weight' => 2,
					'url' => 'admin/fields/groups/',
					'sinonims' => array(
						array('admin', 'fields', 'groups', 'manage_fields', '%')
					),
				),
			),
		);
		
		return $menu;
	}

	/*public function classes()
	{
		$class['content_fields'] = array(
			'file' => 'content_fields.class.php',
		);
		
		$class['field'] = array(
			'file' => 'field.class.php',
		);
		
		$class['processFieldValue'] = array(
			'file' => 'process_field_value.class.php',
		);
		
		$class['fieldsGroup'] = array(
			'file' => 'fields_group.class.php',
		);
		
		$class['varchar'] = array(
			'file' => 'fields_classes/varchar.class.php',
			'name' => 'String',
			'configuration' => 1,
		);
		
		$class['richtext'] = array(
			'file' => 'fields_classes/richtext.class.php',
			'name' => 'Rich text editor',
			'configuration' => 1,
		);
		
		$class['text'] = array(
			'file' => 'fields_classes/text.class.php',
			'name' => 'Text area',
			'configuration' => 1,
		);
		
		$class['select'] = array(
			'file' => 'fields_classes/select.class.php',
			'name' => 'Select box',
			'configuration' => 1,
		);
		
		$class['checkboxes'] = array(
			'file' => 'fields_classes/checkboxes.class.php',
			'name' => 'Checkboxes group',
			'configuration' => 1,
		);
		
		$class['datetime'] = array(
			'file' => 'fields_classes/datetime.class.php',
			'name' => 'Date Time',
			'configuration' => 1,
		);
		
		$class['datetimerange'] = array(
			'file' => 'fields_classes/datetimerange.class.php',
			'name' => 'Date Time range',
			'configuration' => 1,
		);
		
		$class['website'] = array(
			'file' => 'fields_classes/website.class.php',
			'name' => 'Website',
			'configuration' => 1,
		);
		
		$class['email'] = array(
			'file' => 'fields_classes/email.class.php',
			'name' => 'Email',
			'configuration' => 0,
		);
		
		$class['price'] = array(
			'file' => 'fields_classes/price.class.php',
			'name' => 'Price',
			'configuration' => 1,
		);

		return $class;
	}*/
}
?>