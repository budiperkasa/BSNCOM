<?php

function js_advertisement_append($CI)
{
	$CI->load->model('js_advertisement', 'js_advertisement');
	
	$blocks = $CI->js_advertisement->getJsAdvertisementArray();
	
	foreach ($blocks AS $block) {
		if (!empty($block['code'])) {
			$view = $CI->load->view();
			$view->assign('js_advertisement_code', $block['code']);
			
			// Find the most relevant template, according to js block ID
			if ($view->template_exists('js_advertisement/js_advertisement_block-' . $block['id'] . '.tpl')) {
				$template = 'js_advertisement/js_advertisement_block-' . $block['id'] . '.tpl';
			} else {
				$template = 'js_advertisement/js_advertisement_block.tpl';
			}
			$html = $view->fetch($template);

			view::setViewTrigger($block['mode'], $block['selector'], null, null, null, $html);
		}
	}
}
?>