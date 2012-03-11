<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function includeJQuery($CI)
{
	$view = $CI->load->view();
	/*$view->addJsFile('jquery-1.4.4.min.js');
	$view->addJsFile('ui/jquery-ui-1.8.9.custom.min.js');*/
	$view->addExternalJsFile('https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js');
	$view->addExternalJsFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js');
	$view->addCssFile('ui/jquery-ui-1.8.9.custom.css');
	
}

function includeJsCssFiles($CI)
{
	$system_settings = registry::get('system_settings');
	$view = $CI->load->view();
	
	$view->addCssFile('admin_style.css');
	$view->addCssFile('nyroModal.css');
	$view->addCssFile('ui/query-ui-customizations.css');
	$view->addCssFile('jquery.jgrowl.css');

	$view->addJsFile('jquery.jqURL.js');
	$view->addJsFile('jquery.nyroModal.custom.js');
	$view->addJsFile('jquery.jgrowl_compressed.js');
	$view->addJsFile('js_functions.js');
	// There were some problems with full 'cookie.js' name of this file, so now it was renamed to 'coo_kie.js'
	$view->addJsFile('jquery.coo_kie.js');
	$view->addJsFile('jquery.treeview.js');
	$view->addJsFile('phprpc/phpserializer.js');
	$view->addJsFile('phprpc/utf.js');
	$view->addJsFile('jquery.form.js');
	$view->addJsFile('swfobject.js');
	$view->addJsFile('ui/jquery-ui.multidatespicker.js');

	$language_code = registry::get('current_language');
	if ($language_code && $language_code != 'en')
		$view->addJsFile('content_fields/i18n/jquery-ui-i18n.js');

	$view->addExternalJsFile('http://maps.google.com/maps/api/js?v=3.4&sensor=false&language=' . $language_code);
}

function buildAdminMenu($CI)
{
	$menu_list = getMenuItems($CI);

	$return_sinonims = array();
	// Render menu list into $html var
	$html = '';
	renderMenuRecursive($menu_list, $html, $return_sinonims);

	$view = $CI->load->view();
	$js = '';
	if (isset($return_sinonims['sinonims'])) {
		foreach ($return_sinonims['sinonims'] AS $key=>$sinonims) {
			$sinonim_str = "new Array('";
			$sinonim_str .= implode("', '", $sinonims);
			$sinonim_str .= "')";
			$js .= 'sinonims[' . $key . '] = ' . $sinonim_str . '; ';
		}
		$view->assign('sinonims_sinonim_input', $js);

		$js = '';
		foreach ($return_sinonims['url'] AS $key=>$url) {
			$js .= 'urls[' . $key . '] = "' . $url . '"; ';
		}
		$view->assign('sinonims_url_input', $js);
	}

	$view->assign('main_menu_list', $html);
	return $view->fetch('backend/admin_main_menu.tpl');
}

function buildFrontendMenu($CI)
{
	$menu_list = getMenuItems($CI);

	$return_sinonims = array();
	// Render menu list into $html var
	$html = '';
	renderMenuRecursive($menu_list, $html, $return_sinonims);

	$view = $CI->load->view();
	$js = '';
	if (isset($return_sinonims['sinonims'])) {
		foreach ($return_sinonims['sinonims'] AS $key=>$sinonims) {
			$sinonim_str = "new Array('";
			$sinonim_str .= implode("', '", $sinonims);
			$sinonim_str .= "')";
			$js .= 'sinonims[' . $key . '] = ' . $sinonim_str . '; ';
		}
		$view->assign('sinonims_sinonim_input', $js);

		$js = '';
		foreach ($return_sinonims['url'] AS $key=>$url) {
			$js .= 'urls[' . $key . '] = "' . $url . '"; ';
		}
		$view->assign('sinonims_url_input', $js);
	}

	$view->assign('main_menu_list', $html);
	return $view->fetch('frontend/users_main_menu.tpl');
}


function getMenuItems($CI)
{
	events::callEvent('Build menu');

	if ($menu_list = registry::get('menu')) {
		$user_group_id = $CI->session->userdata('user_group_id');
	
		$CI->load->model('acl', 'acl');
		// Build access table accordingly to user group
		$access_table = $CI->acl->getAccessTableForUserGroup($user_group_id);
		// Filter menu items accordingly to access table
		setAccessRecursive($menu_list, $access_table);
		// Sort menu items by weight
		sortMenuRecursive($menu_list);
		
		return $menu_list;
	} else {
		return array();
	}
}
    
/**
* filters menu items accordingly to access table
*
* @param array $menu_list
* @param array $access_table
*/
function setAccessRecursive(&$menu_list, $access_table)
{
	foreach ($menu_list AS $key=>$menu_item) {
		// Look through access items
		if (isset($menu_item['access'])) {
			if (!is_array($menu_item['access']))
				$menu_item['access'] = array($menu_item['access']);

			if (array_key_exists('AND', $menu_item['access'])) {
				// If there AND connector - all accesses must be in access table
				foreach ($menu_item['access'] as $menu_access_item) {
					if (!in_array($menu_access_item, $access_table)) {
						unset($menu_list[$key]);
						break;
					}
				}
			} else {
				// If there no connectors or they are OR - at least one access must be in access table
				$access = false;
				foreach ($menu_item['access'] as $menu_access_item) {
					foreach ($access_table AS $access_item) {
						if ($access_item == $menu_access_item) {
							$access = true;
							break;
						}
					}
				}
				if (!$access) {
					unset($menu_list[$key]);
				}
			}
		}
		
		// Remove items without any children
		if (isset($menu_list[$key]['children'])) {
			setAccessRecursive($menu_list[$key]['children'], $access_table);
			if (!count($menu_list[$key]['children']))
				unset($menu_list[$key]);
		}
	}
}

/**
* sorts menu array accordingly to weight index,
* if weight index wasn't set - menu item becomes in the last order with weight = $count
*
* @param array $menu_list
* @param int $count
*/
function sortMenuRecursive(&$menu_list, &$count = 1000)
{
	$tmp_array = array();
	foreach ($menu_list AS $key=>$menu_item) {
		if (!isset($menu_item['weight']) || !is_numeric($menu_item['weight'])) {
			$menu_list[$key]['weight'] = $count;
			$count++;
		}
		if (!in_array($menu_list[$key]['weight'], $tmp_array))
			$tmp_array[] = $menu_list[$key]['weight'];
		else
			$tmp_array[] = $menu_list[$key]['weight'] + 0.01;
	}
	sort($tmp_array);
	$res_array = array();
	foreach ($tmp_array AS $tmp_weight) {
		foreach ($menu_list AS $key=>$menu_item) {
			if ($menu_item['weight'] == $tmp_weight) {
				$res_array[$key] = $menu_item;
				if (isset($menu_item['children']))
					sortMenuRecursive($res_array[$key]['children'], $count);
			}
		}
	}
	$menu_list = $res_array;
}

/**
* renders menu_list into html template
*
* @param array $menu_list
* @param string $html
*/
function renderMenuRecursive($menu_list, &$html, &$sinonims)
{
	foreach ($menu_list AS $menu_key=>$menu_item) {
		if (isset($menu_item['children'])) {
			$class = 'folder';
			$link_class = '';
			$open = '';
		} else {
			if (isset($menu_item['icon']))
				$class = $menu_item['icon'];
			else 
				$class = 'file';

			if (isset($menu_item['sinonims'])) {
				if (is_array($menu_item['sinonims']))
					foreach ($menu_item['sinonims'] AS $sinonim) {
						$sinonims['url'][] = site_url($menu_item['url']);
						$sinonims['sinonims'][] = $sinonim;
					}
			}
		}

		if (isset($menu_item['weight']))
			$weight = $menu_item['weight'];
		else 
			$weight = 'null';

		if (isset($menu_item['children'])) {
			$html .= '<li id="weight_' . $weight . '"><span class="' . $class .' menu_item">' . $menu_key . '</span>
			';
			$html .= '<ul>';
			renderMenuRecursive($menu_item['children'], $html, $sinonims);
			$html .= '</ul>';
		} else {
			$html .= '<li id="weight_' . $weight . '"><span class="' . $class . ' menu_item">' . anchor($menu_item['url'], $menu_key) . '</span>';
		}
		$html .= '</li>
		';
	}
}

/**
 * builds messages block and append it to the next rendered page under H3 title tag
 *
 * @param superobject $CI
 */
function buildMessagesBlock($CI)
{
	$html ='';

	// get messages from session or from registry
	if (!$success_msgs = $CI->session->flashdata('success_msgs')) {
		$success_msgs = registry::get('success_msgs');
	}
	if ($success_msgs && !empty($success_msgs)) {
		$view = $CI->load->view();
		$view->assign('success_msgs', $success_msgs);
		$html .= $view->fetch('backend/success_messages.tpl');
	}
	
	// get messages from session or from registry
	if (!$error_msgs = $CI->session->flashdata('error_msgs')) {
		$error_msgs = registry::get('error_msgs');
	}
	if ($error_msgs && !empty($error_msgs)) {
		$view = $CI->load->view();
		$view->assign('error_msgs', $error_msgs);
		$html .= $view->fetch('backend/error_messages.tpl');
	}

	if ($html != '') {
		$CI->session->set_flashdata('success_msgs', array());
		$CI->session->set_flashdata('error_msgs', array());
		return $html;
	}
}

function buildBreadcrumbs($CI)
{
	$html ='';

	if($breadcrumbs = registry::get('breadcrumbs')) {
		$view = $CI->load->view();
		$view->assign('breadcrumbs', $breadcrumbs);
		$html .= $view->fetch('backend/breadcrumbs.tpl');
	}

	if ($html != '') {
		return $html;
	}
}
?>