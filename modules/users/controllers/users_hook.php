<?php
include_once(MODULES_PATH . 'users/classes/user.class.php');

/**
* Append authorized user's name to the 'view my profile' menu item
*
*/
function buildMyProfileMenuItem($CI)
{
	$menu_list = registry::get('menu');

	$my_profile_key = $menu_list[LANG_EDIT_MY_PROFILE_MENU];
	unset($menu_list[LANG_EDIT_MY_PROFILE_MENU]);

	$user_login = $CI->session->userdata('user_login');
	$menu_list[LANG_EDIT_MY_PROFILE_MENU . ' <b>(' . $user_login  . ')</b>'] = $my_profile_key;
		
	registry::set('menu', $menu_list);
}
?>