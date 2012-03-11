<?php
class facebookModule
{
	public $title = "Facebook Integration module";
	public $version = "0.2";
	public $description = "Provides ability to login into directory using facebook credentials.";
	public $type = 'custom';

	public $lang_files = "facebook.php";

	public function hooks()
	{
		$hook['fcb_login'] = array(
			'weight' => 0,
			'exclusions' => array(
				'locations/build_drop_box',
				'locations/ajax_autocomplete_request/',
				'locations/get_locations_path_by_id/',
				'admin/languages/translate_block/:any',
				'admin/languages/translate_window/:any',
				'ajax/(.*)',
				'refresh_captcha/',
			),
		);
		
		$hook['fcb_logout'] = array(
			'weight' => 0,
			'inclusions' => array(
				'logout/',
			),
		);
		
		$hook['fcb_login_btn'] = array(
			'viewTrigger' => array('post', '#login_form'),
		);

		$hook['fcb_systemSettingsPage'] = array(
			'viewTrigger' => array('preouter', '.save_button'),
			'inclusions' => array(
				'admin/settings/services/'
			),
		);
		$hook['fcb_handleSystemSettings'] = array(
			'weight' => 0,
			'inclusions' => array(
				'admin/settings/services/'
			),
		);

		return $hook;
	}
}
?>