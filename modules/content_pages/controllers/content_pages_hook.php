<?php
include_once(MODULES_PATH . 'acl/classes/content_acl.class.php');

// top menu content pages links
function buildContentPagesMenu_top($CI)
{
	$cache_index = 'content_pages_links_top_' . $CI->session->userdata('user_group_id');
	if (!$cache = $CI->cache->load($cache_index)) {
		$CI->load->model('content_pages', 'content_pages');

		$content_pages = $CI->content_pages->getContentPagesForFront();

		// don't register view triggers for this view, nothing could be directly attached, 
		// only through template display() function
		$view = $CI->load->view(false);
		$view->assign('content_pages', $content_pages);
	   	$html = $view->fetch('frontend/content-pages-menu-top.tpl');

		$CI->cache->save($html, $cache_index, array('content_pages'));
	} else {
    	$html = $cache;
    }
    return $html;
}

// footer menu content pages links
function buildContentPagesMenu_bottom($CI)
{
	$cache_index = 'content_pages_links_bottom' . $CI->session->userdata('user_group_id');
	if (!$cache = $CI->cache->load($cache_index)) {
		$CI->load->model('content_pages', 'content_pages');

		$content_pages = $CI->content_pages->getContentPagesForFront();

		// don't register view triggers for this view, nothing could be directly attached, 
		// only through template display() function
		$view = $CI->load->view(false);
		$view->assign('content_pages', $content_pages);
		$html = $view->fetch('frontend/content-pages-menu-bottom.tpl');

		$CI->cache->save($html, $cache_index, array('content_pages'));
	} else {
    	$html = $cache;
    }
    return $html;
}
?>