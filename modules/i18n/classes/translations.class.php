<?php

class translations
{
	/**
	 * saves translated inputs for quite new rows of the DB (where id were 'new')
	 *
	 * @return arrays of ('table', 'field', 'row_id', 'virtual_id')
	 */
	public static function saveTranslations()
	{
		$args = func_get_args();
		
		$CI = &get_instance();
		$CI->load->model('languages', 'i18n');
		
		if ($CI->session->userdata('translation_array')) {
			$translation_array = unserialize(base64_decode($CI->session->userdata('translation_array')));
		}

		if (isset($translation_array)) {
			foreach ($args AS $translation) {
				$table = $translation[0];
				$field = $translation[1];
				$row_id = $translation[2];
				if (isset($translation[3]))
					$virtual_id = $translation[3];
				else 
					$virtual_id = 0;

				if (isset($translation_array[$table][$field][$virtual_id])) {
					foreach ($translation_array[$table][$field][$virtual_id] AS $lang_code=>$value) {
						$CI->languages->setLangAreasField($lang_code, $value, $table, $field, $row_id);
					}
					unset($translation_array[$table][$field][$virtual_id]);
					$CI->session->set_userdata('translation_array', base64_encode(serialize($translation_array)));
				}
			}
		}
	}
	
	/**
	 * builds link, that opens choose language dialog window
	 *
	 * @param string $table
	 * @param string $field
	 * @param string $row_id
	 * @param string $field_type
	 * @param int $virtual_id
	 * @return html
	 */
	public static function getTranslationLink($table, $field, $row_id, $field_type, $virtual_id = '')
	{
		$public_path = registry::get('public_path');
		
		$system_settings = registry::get('system_settings');
		
		$i18n_fields = registry::get('i18n_fields');
	
		if (isset($i18n_fields[$table]) && in_array($field, $i18n_fields[$table])) {
			if (isset($system_settings['language_areas_enabled']) && $system_settings['language_areas_enabled']) {
				// prevent hack access to translatable content,
				// user will be able to translate content ONLY via this link
				$hash = md5($table.$field.$row_id.$field_type.registry::get('secret'));
	
				$url = site_url('index.php/admin/languages/translate_block/' . $table . '/' . $field . '/' . $row_id . '/' . $hash . '/' . $field_type . '/' . $virtual_id);
				return '<a href="' . $url . '" title="' . LANG_TRANSLATE_CONTENT . '" class="popup_dialog"><img src="' . $public_path . 'images/buttons/text_allcaps.png"></a>';
			}
		}
	}
}
?>