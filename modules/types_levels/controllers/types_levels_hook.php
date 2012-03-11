<?php

function buildMenu($CI)
{
	$CI->load->model('types', 'types_levels');
    $types = $CI->types->getTypesLevels();

	/*
    $types[x]['id']
    $types[x]['name']
    $types[x]['order_num']
    $types[x]['levels'][y]['id']
    $types[x]['levels'][y]['name']
    $types[x]['levels'][y]['order_num']
    */
		
	$types_array = array();
	$content_fields_groups_menu = array();
	$types_key_duplicate = 0;
	$levels_key_duplicate = 0;
	foreach ($types AS $type) {
		$type_levels = array();

		$levels_array = array();
		if ($type->levels)
			foreach ($type->levels AS $level) {
				// If is the same level name in levels array, then level name will be modified
				if (array_key_exists($level->name, $levels_array))
					$level->name = $level->name . '_' . ++$levels_key_duplicate;
	
				$levels_array[$level->name] = array(
					'url' => 'admin/levels/edit/' . $level->id . '/',
					'sinonims' => array(array('admin', 'levels', 'delete', $level->id)),
				);
			}

		$type_levels[LANG_LEVELS_MENU] = array(
			'children' => $levels_array,
		);

		// If is the same type name in types array, then type name will be modified
		if (array_key_exists($type->name, $types_array))
			$type->name = $type->name . '_' . ++$types_key_duplicate;
			
		$types_array[$type->name] = array(
			'children' => array(
				LANG_EDIT_TYPE_MENU => array(
					'url' => 'admin/types/edit/' . $type->id . '/',
					'icon' => 'filesel',
					'sinonims' => array(array('admin', 'types', 'delete', $type->id)),
				),
				LANG_CREATE_LEVEL_MENU => array(
					'url' => 'admin/levels/create/type_id/' . $type->id . '/',
					'icon' => 'filenew',
				),
				LANG_VIEW_LEVELS_MENU => array(
					'url' => 'admin/levels/type_id/' . $type->id . '/',
				),
			),
		);
		// Use union operator instead of array_merge, because type name may have numeric name
		$types_array[$type->name]['children'] += $type_levels;
	}

	$menu_list = registry::get('menu');
	// Use union operator instead of array_merge, because type name may have numeric name
	$menu_list[LANG_TYPES_LEVELS_MENU]['children'] += $types_array;
	
	// If there is one or more types in the system and single type mode enabled - 
	// we will prevent new types creation
	$system_settings = registry::get('system_settings');
    $single_type_structure = $system_settings['single_type_structure'];
	if ($single_type_structure && count($types) >= 1) {
		unset($menu_list[LANG_TYPES_LEVELS_MENU]['children'][LANG_CREATE_TYPE_MENU]);
		// also disable 'categories by type' management
		unset($menu_list[LANG_CATEGORIES_MENU]['children'][LANG_MANAGE_CATEGORIES_BY_TYPE_MENU]);
		// also disable 'search by type' management
		unset($menu_list[LANG_SEARCH_MENU]['children'][LANG_MANAGE_SEARCH_BYTYPE_MENU]);
	}

	registry::set('menu', $menu_list);
}
?>