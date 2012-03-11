<?php
class i18nModule
{
	public $title = "Internationalization";
	public $version = "0.4";
	public $description = "Module provides internationalization of interface and entered content.";
	public $type = 'custom';
	public $permissions = array('Set I18n');

	public $lang_files = "i18n.php";

	public function routes()
	{
		$route['admin/languages/'] = array(
			'title' => LANG_SITE_LANGUAGES_TITLE,
			'access' => 'Set I18n',
		);

		$route['admin/languages/create/'] = array(
			'title' => LANG_CREATE_LANGUAGE_TITLE,
			'action' => 'language_create',
			'access' => 'Set I18n',
		);
		
		$route['admin/languages/edit/:num/'] = array(
			'title' => LANG_EDIT_LANGUAGE_TITLE,
			'action' => 'language_edit',
			'access' => 'Set I18n',
		);
		
		$route['admin/languages/delete/:num/'] = array(
			'title' => LANG_DELETE_LANGUAGE_TITLE,
			'action' => 'language_delete',
			'access' => 'Set I18n',
		);

		$route['admin/languages/translate/:num/'] = array(
			'title' => LANG_TRANSLATE_INTERFACE_TITLE,
			'action' => 'languages_translate',
			'access' => 'Set I18n',
		);
		
		$route['admin/languages/translate/:num/file_id/:num/'] = array(
			'title' => LANG_TRANSLATE_INTERFACE_TITLE,
			'action' => 'languages_translate_file',
			'access' => 'Set I18n',
		);
		$route['admin/languages/translate_text/:num/'] = array(
			'title' => LANG_TRANSLATE_INTERFACE_TITLE,
			'action' => 'languages_translate_file',
			'access' => 'Set I18n',
		);
		
		$route['admin/languages/languages_translate_search_text/:num/'] = array(
			'title' => LANG_TRANSLATE_INTERFACE_TITLE,
			'action' => 'languages_translate_search_text',
			'access' => 'Set I18n',
		);
		
		$route['ajax/languages/get_translation_link/([a-z0-9_]+)/([a-z0-9_]+)/([a-z0-9_]+)/([a-z0-9_]+)/([a-z0-9_]+)/'] = array(
			'action' => 'ajax_get_translation_link'
		);
		
		// The same routes, just differs by field_type params
		$route['admin/languages/translate_block/([a-z0-9_]+)/([a-z0-9_]+)/([a-z0-9_]+)/([a-z0-9]+)/'] = array(
			'action' => 'translate_block'
		);
		$route['admin/languages/translate_block/([a-z0-9_]+)/([a-z0-9_]+)/([a-z0-9_]+)/([a-z0-9]+)/field_type/([a-z0-9_]+)/'] = array(
			'action' => 'translate_block'
		);
		$route['admin/languages/translate_block/([a-z0-9_]+)/([a-z0-9_]+)/([a-z0-9_]+)/([a-z0-9]+)/([a-z0-9_]+)/'] = array(
			'action' => 'translate_block'
		);
		$route['admin/languages/translate_block/([a-z0-9_]+)/([a-z0-9_]+)/([a-z0-9_]+)/([a-z0-9]+)/([a-z0-9_]+)/([a-z0-9_]+)/'] = array(
			'action' => 'translate_block'
		);
		
		$route['admin/languages/translate_window/([a-z0-9_]+)/([a-z0-9_]+)/([a-z0-9_]+)/([a-z0-9_]*)/([a-z0-9]+)/([0-9]+)/([a-z_]+)/'] = array(
			'title' => LANG_TRANSLATE_DIALOG_TITLE,
			'action' => 'translate_window'
		);
		
		$route['admin/i18n_settings/'] = array(
			'title' => LANG_I18N_SETTINGS_TITLE,
			'action' => 'i18n_settings',
			'acccess' => 'Edit system settings',
		);

		return $route;
	}

	public function menu()
	{
		$menu[LANG_LANGUAGES_MENU] = array(
			'weight' => 55,
			'access' => 'Set I18n',
			'children' => array(
				LANG_MANAGE_LANGUAGES_MENU => array(
					'url' => 'admin/languages/',
					'sinonims' => array(
						array('admin', 'languages', 'create'), 
						array('admin', 'languages', 'edit', '%'), 
						array('admin', 'languages', 'delete', '%'), 
						array('admin', 'languages', 'translate', '%+'),
						array('admin', 'languages', 'languages_translate_search_text', '%+'),
					)
				),
			),
		);
		
		$menu[LANG_SETTINGS_MENU] = array(
			'children' => array(
				LANG_MISCELLANEOUS_SETTINGS_MENU => array(
					'children' => array(
						LANG_I18N_SETTINGS_MENU => array(
							'url' => 'admin/i18n_settings/',
							'action' => 'i18n_settings',
							'access' => 'Edit system settings',
						),
					),
				),
			),
		);

		return $menu;
	}
	
	public function hooks()
	{
		/*$hook['i18n_systemSettingsPage'] = array(
			'viewTrigger' => array('preouter', '.save_button'),
			'inclusions' => array(
				'admin/settings/'
			),
		);
		$hook['i18n_handleSystemSettings'] = array(
			'weight' => 0,
			'inclusions' => array(
				'admin/settings/'
			),
		);*/

		$hook['loadI18nItemsList'] = array(
			'weight' => 0,
		);
		
		$hook['initI18nModule'] = array(
			'weight' => 2,
		);
		
		/*$hook['buildLanguagesPanels'] = array(
			'viewTrigger' => array('pre', '#header_right'),
			'exclusions' => array(
				'locations/build_drop_box',
				'locations/ajax_autocomplete_request/',
				'admin/languages/translate_block/:any',
				'admin/languages/translate_window/:any',
				'admin/locations/ajax_locations_request/',
				'refresh_captcha/',
				'locations/get_locations_path_by_id/',
			),
		);*/
		
		$hook['modifyQuery'] = array(
			'events' => array('onExecQuery'),
			'exclusions' => array(
				'admin/languages/translate_block/:any',
				'admin/languages/translate_window/:any',
				'refresh_captcha/',
			),
		);
		
		$hook['setSelectI18nConditions'] = array(
			'events' => array('onQuerySelect'),
			'exclusions' => array(
				'admin/languages/translate_block/:any',
				'admin/languages/translate_window/:any',
				'refresh_captcha/',
			),
		);
		
		$hook['setTableI18nConditions'] = array(
			'events' => array('onQueryFrom', 'onQueryJoin'),
			'exclusions' => array(
				'admin/languages/translate_block/:any',
				'admin/languages/translate_window/:any',
				'refresh_captcha/',
			),
		);
		
		$hook['setWhereI18nConditions'] = array(
			'events' => array('onQueryWhere'),
			'exclusions' => array(
				'admin/languages/translate_block/:any',
				'admin/languages/translate_window/:any',
				'refresh_captcha/',
			),
		);
		
		$hook['setLikeI18nConditions'] = array(
			'events' => array('onQueryLike'),
			'exclusions' => array(
				'admin/languages/translate_block/:any',
				'admin/languages/translate_window/:any',
				'refresh_captcha/',
			),
		);
		
		$hook['setQuerySetI18n'] = array(
			'events' => array('onQuerySet'),
			'exclusions' => array(
				'admin/languages/translate_block/:any',
				'admin/languages/translate_window/:any',
				'refresh_captcha/',
			),
		);
		
		$hook['setQueryInsertUpdateI18n'] = array(
			'events' => array('onQueryInsert', 'onQueryUpdate'),
			'exclusions' => array(
				'admin/languages/translate_block/:any',
				'admin/languages/translate_window/:any',
				'refresh_captcha/',
			),
		);
		
		$hook['resetQuery'] = array(
			'events' => array('onResetQuery'),
			'exclusions' => array(
				'admin/languages/translate_block/:any',
				'admin/languages/translate_window/:any',
				'refresh_captcha/',
			),
		);
		
		return $hook;
	}
}
?>