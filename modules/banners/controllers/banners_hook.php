<?php
include_once(MODULES_PATH . 'notifications/classes/notification_sender.class.php');

/**
 * set banners view triggers
 *
 * @param super object $CI
 */
function banners_append($CI)
{
	$CI->load->model('banners_blocks', 'banners');
	$CI->load->model('banners', 'banners');
	
	$blocks = $CI->banners_blocks->getBannersBlocks();
	
	foreach ($blocks AS $block) {
		if ($banners = $CI->banners->getActiveBannersOfBlock($block->id)) {
			$view_banner = $CI->banners->chooseBannerToView($banners);

			view::setViewTrigger($block->mode, $block->selector, 'banners_hook.php', 'banners', 'render_banner', null, array($view_banner));
		}
	}
}

/**
 * render banner
 *
 * @param super object $CI
 * @param array $args - banner and block objects
 * @return html
 */
function render_banner($CI, $args)
{
	$view_banner = array_shift($args);

	$CI->load->model('banners', 'banners');
	$CI->banners->incrementView($view_banner->id);
	
	$cache_index = 'banner_' . $view_banner->id . '_' . $CI->session->userdata('user_group_id');
	if (!$cache = $CI->cache->load($cache_index)) {
		$view = $CI->load->view();
		$view->assign('banner', $view_banner);

		// Find the most relevant template, according to banners block ID
		if ($view->template_exists('banners/banner_block-' . $view_banner->block->id . '.tpl')) {
			$template = 'banners/banner_block-' . $view_banner->block->id . '.tpl';
		} else {
			$template = 'banners/banner_block.tpl';
		}
		$html = $view->fetch($template);
		
		$CI->cache->save($html, $cache_index, array('banners'));
	} else {
    	$html = $cache;
    }

    return $html;
}

function addBannersAdvertise($CI)
{
	$content_access_obj = contentAcl::getInstance();
	if ($content_access_obj->isPermission('Create banners')) {
		$CI->load->model('banners_blocks', 'banners');
	    $banners_blocks = $CI->banners_blocks->getBannersBlocks();
	    $prices_array = array();
	    if ($CI->session->userdata('user_id'))
	    	$banners_prices = registry::get('banners_prices');
	    else 
	    	$banners_prices = registry::get('default_banners_prices');

	    if ($banners_prices) {
	    	foreach ($banners_blocks AS $block_key=>$block) {
	    		foreach ($banners_prices AS $price) {
	    			if ($block->id == $price['block_id']) {
	    				$prices_array[$block->id]['price_currency'] = $price['currency'];
	    				$prices_array[$block->id]['price_value'] = $price['value'];
	    			}
	    		}
	    	}
	    }
	
	    $view = $CI->load->view();
	    $view->assign('banners_blocks', $banners_blocks);
	    $view->assign('banners_prices', $prices_array);
	    return $view->fetch('banners/advertise_with_us.tpl');
	} else {
		return false;
	}
}

/**
* Append my banners count to the 'View my banners' menu item
*
*/
function buildMyBannersMenuItem($CI)
{
	if ($CI->session->userdata('user_id')) {
		$CI->load->model('banners', 'banners');
		$banners_count = $CI->banners->getMyBannersCount();
		if ($banners_count) {
			$menu_list = registry::get('menu');
	
			$my_banners_key = $menu_list[LANG_BANNERS_MY_MENU];
			unset($menu_list[LANG_BANNERS_MY_MENU]);
	
			$menu_list[LANG_BANNERS_MY_MENU . ' <b>(' . $banners_count  . ')</b>'] = $my_banners_key;
			registry::set('menu', $menu_list);
		}
	}
}

/**
 * set banner upload path and allowed formats
 *
 * @param super object $CI
 */
function defineBannersConfig($CI)
{
	$CI->config->load(MODULES_PATH . 'banners'.DIRECTORY_SEPARATOR.'banners.config.php');
}

/**
 * block banners automatically
 *
 * @param super object $CI
 */
function banners_runAutoBlocker($CI)
{
	$CI->load->model('banners', 'banners');
	$CI->banners->suspendExpiredActiveBanners();
}
?>