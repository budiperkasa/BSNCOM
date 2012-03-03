<?php
include_once(MODULES_PATH . 'payment/classes/goods_content.class.php');
include_once(MODULES_PATH . 'acl/classes/content_acl.class.php');

class packagesGoods extends goodsContentClass
{
	/**
	 * the real name of goods
	 *
	 * @var string
	 */
	public $name = LANG_PACKAGES_GOODS;

	/**
	 * alias name of goods in the DB
	 *
	 * @var string
	 */
	public $category = 'packages';
	
	public function showUrl()
	{
		return false;
	}

	/**
	 * select goods object ID and title from DB
	 *
	 */
	public function setItemAttrs($invoice_id)
	{
	    $CI = &get_instance();
		$CI->db->select('pu.id as goods_id');
	    $CI->db->select('p.name as goods_title');
	    $CI->db->from('packages_users as pu');
	    $CI->db->join('packages as p', 'pu.package_id=p.id', 'left');
	    $CI->db->join('invoices as i', 'i.goods_id=pu.id', 'left');
	    $CI->db->where('i.id', $invoice_id);
	    $query = $CI->db->get();
	    if ($row = $query->row_array()) {
		    $this->goods_id = $row['goods_id'];
		    $this->goods_title = LANG_PACKAGE_INVOICE_TITLE . ": '" . $row['goods_title'] . "'";
	    }
	}

	/**
	 * render goods options on the invoice details page
	 *
	 * @return html
	 */
	public function showOptions()
	{
		$CI = &get_instance();
		$CI->load->model('packages', 'packages');
		$package = $CI->packages->getPackageByUsersPackage($this->goods_id);
		//var_dump($package);
		
    	//$CI->load->model('types', 'types_levels');
    	//$types = $CI->types->getTypesLevels();
    	
    	$CI->load->model('levels', 'types_levels');
    	//$types = $CI->levels->getLevels();
		
		$system_settings = registry::get('system_settings');
		$content_access_obj = contentAcl::getInstance();
		
		// --------------------------------------------------------------------------------------------
		// Show listings count
		// --------------------------------------------------------------------------------------------
		$result = '<tr><td class="table_left_side">' . LANG_PACKAGE_LISTINGS_COUNT . '</td>';
		$result .= '<td class="table_right_side">';
		foreach ($package->items AS $level_id=>$listings_count) {
			$level = $CI->levels->getLevelById($level_id);
			if ($content_access_obj->isContentPermission('levels', $level_id)) {
				if ($listings_count > 0 || $listings_count === 'unlimited') {
					$type = $level->getType();
					$result .= '<b>';
					if (!$system_settings['single_type_structure']) {
						$result .= $type->name . ' - ';
					}
					$result .= '<i>' . $level->name . '</i>:</b> ';
					if ($listings_count !== 'unlimited')
						$result .= $listings_count . ' ' . LANG_LISTINGS;
					else
						$result .= '<span class="green">' . LANG_UNLIMITED . '</span>';
					$result .= '<br />';
				}
			}
		}
		$result .= '</td></tr>';
		
		/*{foreach from=$user_package->listings_left key=level_id item=listings_count}
								{if $content_access_obj->isContentPermission('levels', $level_id)}
									{if $listings_count > 0 || $listings_count === 'unlimited'}
										{assign var=type value=$user_package->package->levels[$level_id]->getType()}
										<b>{if !$system_settings.single_type_structure}{$type->name} - {/if}<i>{$user_package->package->levels[$level_id]->name}</i>:</b> {if $listings_count !== 'unlimited'}{$listings_count} {$LANG_LISTINGS}{else}<span class="green">{$LANG_UNLIMITED}</span>{/if}<br />
										{assign var="package_available_for_user" value=1}
									{/if}
								{/if}*/
		
		
		/*$result = '<tr><td class="table_left_side">' . LANG_PACKAGE_LISTINGS_COUNT . '</td>';
		$result .= '<td class="table_right_side"><table class="standardTable" border="0" cellpadding="2" cellspacing="2">';
		$result .= '<tr>';
		if (!$system_settings['single_type_structure']) {
			$result .= '<th>' . LANG_TYPE_TH . '</th>';
		}
		$result .= '<th>' . LANG_LEVEL_TH . '</th>
					<th>' . LANG_PACKAGE_LISTINGS_COUNT . '</th>
					</tr>';

		foreach ($types AS $type) {
			$result .= '<tr>';
			if (!$system_settings['single_type_structure']) {
				$result .= '<td class="td_header">';
				$result .= $type->name;
				$result .= '</td>';
			}
			$result .= '<td class="td_header">';
			if ($type->levels) {
				$result .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
				foreach ($type->levels AS $level) {
					if ($content_access_obj->isContentPermission('levels', $level->id)) {
						if ($package->items[$level->id] > 0 || $package->items[$level->id] === 'unlimited') {
							$result .= '<tr>
										<td class="td_header" width="100%" style="border: 0;">';
							$result .= $level->name;
							$result .= '</td>
										</tr>';
						}
					}
				}
				$result .= '</table>';
			} else {
				$result .= '&nbsp;';
			}
			$result .= '</td>
						<td>';
			if ($type->levels) {
				$result .= '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
				foreach ($type->levels AS $level) {
					if ($content_access_obj->isContentPermission('levels', $level->id)) {
						if ($package->items[$level->id] > 0 || $package->items[$level->id] === 'unlimited') {
							$result .= '<tr>
										<td class="td_header" width="100%" style="border: 0;">';
							if ($package->items[$level->id] === 'unlimited')
								$result .= LANG_UNLIMITED;
							else
								$result .= $package->items[$level->id];
							$result .= '</td>
										</tr>';
						}
					}
				}
				$result .= '</table>';
			} else {
				$result .= '&nbsp;';
			}
			$result .= '</td></tr>';
		}
		$result .= '</table></td></tr>';*/

		return $result;
	}
	
	/**
	 * complete transaction and invoice of the goods item
	 *
	 * @param int $quantity
	 * @return bool
	 */
	public function completePayment($quantity)
	{
		$user_package_id = $this->goods_id;

		$CI = &get_instance();
		$CI->load->model('packages', 'packages');
		return $CI->packages->savePackageStatus($user_package_id, 1);
	}
}
?>