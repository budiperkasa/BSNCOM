<?php

/**
* Append my packages count to the 'View my packages' menu item
*
*/
function buildMyPackagesMenuItem($CI)
{
	if ($CI->session->userdata('user_id')) {
		$menu_list = registry::get('menu');
		
		// --------------------------------------------------------------------------------------------
		// Hide packages menu items if 'standalone_mode' setting enabled
		// --------------------------------------------------------------------------------------------
		$system_settings = registry::get('system_settings');
		if ($system_settings['packages_listings_creation_mode'] == 'standalone_mode') {
			unset($menu_list[LANG_VIEW_MY_PACKAGES]);
			unset($menu_list[LANG_ADD_PACKAGE_TITLE]);
	
			registry::set('menu', $menu_list);
		// --------------------------------------------------------------------------------------------
		} else {
			$CI->load->model('packages', 'packages');
			$packages_count = $CI->packages->getMyPackagesCount();
			if ($packages_count) {
				$my_packages_key = $menu_list[LANG_VIEW_MY_PACKAGES];
				unset($menu_list[LANG_VIEW_MY_PACKAGES]);

				$menu_list[LANG_VIEW_MY_PACKAGES . ' <b>(' . $packages_count  . ')</b>'] = $my_packages_key;
				registry::set('menu', $menu_list);
			}
		}
	}
}
?>