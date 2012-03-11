<?php

/**
 * Join invoices count to the 'View my invoices' menu item
 *
 */
function buildMyInvoicesMenuItem($CI)
{
	if ($CI->session->userdata('user_id')) {
		$CI->load->model('payment', 'payment');
		$invoices_count = $CI->payment->getMyInvoicesCount();
		if ($invoices_count) {
			$menu_list = registry::get('menu');
	
			$my_invoices_key = $menu_list[LANG_VIEW_MY_INVOICES_MENU];
			unset($menu_list[LANG_VIEW_MY_INVOICES_MENU]);
	
			$menu_list[LANG_VIEW_MY_INVOICES_MENU . ' <b>(' . $invoices_count  . ')</b>'] = $my_invoices_key;
			registry::set('menu', $menu_list);
		}
	}
}
	
/**
 * Join transactions count to the 'View my transactions' menu item
 *
 */
function buildMyTransactionsMenuItem($CI)
{
	if ($CI->session->userdata('user_id')) {
		$CI->load->model('payment', 'payment');
		$transactions_count = $CI->payment->getMyTransactionsCount();
		if ($transactions_count) {
			$menu_list = registry::get('menu');
	
			$my_transactions_key = $menu_list[LANG_VIEW_MY_TRANSACTIONS_MENU];
			unset($menu_list[LANG_VIEW_MY_TRANSACTIONS_MENU]);
	
			$menu_list[LANG_VIEW_MY_TRANSACTIONS_MENU . ' <b>(' . $transactions_count  . ')</b>'] = $my_transactions_key;
			registry::set('menu', $menu_list);
		}
	}
}
?>