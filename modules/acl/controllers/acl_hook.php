<?php

function check_rights($CI)
{
	if (!($user_group_id = $CI->session->userdata('user_group_id')) || !$CI->session->userdata('user_id')) {
		redirect('login/');
	}
		
	$module_controller_attrs = registry::get('controller_attrs');
	if (isset($module_controller_attrs['access'])) {
		$access = $module_controller_attrs['access'];

		$CI->load->model('acl', 'acl');
		if (!$CI->acl->checkAccess($user_group_id, $access))
			show_error('401 Access denied!', '');
	}
}
?>