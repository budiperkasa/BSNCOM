<?php /* Smarty version 2.6.26, created on 2012-02-06 02:18:28
         compiled from backend/admin_index.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                <div class="content">
                	<?php if ($this->_tpl_vars['content_access_obj']->isPermission('Edit system settings') || $this->_tpl_vars['content_access_obj']->isPermission('Manage all listings') || $this->_tpl_vars['content_access_obj']->isPermission('Manage users') || $this->_tpl_vars['CI']->load->is_module_loaded('banners') && $this->_tpl_vars['content_access_obj']->isPermission('Manage all banners') || $this->_tpl_vars['CI']->load->is_module_loaded('payment') && $this->_tpl_vars['content_access_obj']->isPermission('View all invoices') && $this->_tpl_vars['content_access_obj']->isPermission('View all transactions') || $this->_tpl_vars['content_access_obj']->isPermission('Manage all reviews') || $this->_tpl_vars['content_access_obj']->isPermission('Manage coupons')): ?>
                    <h3><?php echo $this->_tpl_vars['LANG_GLOBAL_SUMMARY_INFO']; ?>
</h3>
                    <?php endif; ?>

                     <?php if ($this->_tpl_vars['content_access_obj']->isPermission('Edit system settings')): ?>
                     <div class="admin_option">
                     	<div class="admin_option_name">
                     		<?php echo $this->_tpl_vars['LANG_SUMMARY_VERSION_INFO']; ?>
: v<?php echo $this->_tpl_vars['system_settings']['W2D_VERSION']; ?>

                     	</div>
                     	<a href="http://www.salephpscripts.com/blog/" target="_blank"><?php echo $this->_tpl_vars['LANG_SUMMARY_VERSION_CHECK_LINK']; ?>
</a>
                     </div>
                     <?php endif; ?>

                     <?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage all listings')): ?>
                     <label class="block_title"><?php echo $this->_tpl_vars['LANG_LISTINGS_SUMMARY']; ?>
</label>
                     <div class="admin_option">
	                     <table>
	                     	<?php $_from = $this->_tpl_vars['listings_status_count']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['status_row']):
?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 1): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_ACTIVE_LISTINGS']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['active_status']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/search/search_status/1"); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW']; ?>
</a>
	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 2): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_BLOCKED_LISTINGS']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['blocked_status']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/search/search_status/2"); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW']; ?>
</a>
	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 3): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_SUSPENDED_LISTINGS']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['suspended_status']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/search/search_status/3"); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW']; ?>
</a>
	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 4): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_UNAPPROVED_LISTINGS']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['unapproved_status']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/search/search_status/4"); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW']; ?>
</a>
	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 5): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_NOTPAID_LISTINGS']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['not_paid_status']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/search/search_status/5"); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW']; ?>
</a>
	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php endforeach; endif; unset($_from); ?>
	                     	<?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage ability to claim')): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_LISTINGS_CLAIMED_STATUS']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['unapproved_claims_count']; ?>
 <?php if ($this->_tpl_vars['unapproved_claims_count']): ?><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/search/search_claimed_listings/claimed/"); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW']; ?>
</a><?php endif; ?>
	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     </table>
                     </div>
                     <?php endif; ?>

                     <?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage users')): ?>
                     <label class="block_title"><?php echo $this->_tpl_vars['LANG_USERS_SUMMARY']; ?>
</label>
                     <div class="admin_option">
	                     <table>
	                     	<?php $_from = $this->_tpl_vars['users_status_count']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['status_row']):
?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 1): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_UNVERIFIED_USERS']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['unverified_status']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/search/search_status/1"); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW']; ?>
</a>
	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 2): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_ACTIVE_USERS']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['active_status']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/search/search_status/2"); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW']; ?>
</a>
	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 3): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_BLOCKED_USERS']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['blocked_status']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/search/search_status/3"); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW']; ?>
</a>
	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php endforeach; endif; unset($_from); ?>
	                     	<?php $_from = $this->_tpl_vars['users_in_groups_count']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['count_row']):
?>
	                     	<?php $this->assign('users_group_id', $this->_tpl_vars['count_row']['id']); ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_USERS_IN_GROUP_STATS']; ?>
 '<?php echo $this->_tpl_vars['count_row']['name']; ?>
':
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['count_row']['users_count_in_group']; ?>
 <?php if ($this->_tpl_vars['count_row']['users_count_in_group']): ?><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/search/search_group/".($this->_tpl_vars['users_group_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW']; ?>
</a><?php endif; ?>
	                     		</td>
	                     	</tr>
	                     	<?php endforeach; endif; unset($_from); ?>
	                     </table>
                     </div>
                     <?php endif; ?>
                     
                     <?php if ($this->_tpl_vars['CI']->load->is_module_loaded('banners') && $this->_tpl_vars['content_access_obj']->isPermission('Manage all banners')): ?>
                     <label class="block_title"><?php echo $this->_tpl_vars['LANG_BANNERS_SUMMARY']; ?>
</label>
                     <div class="admin_option">
	                     <table>
	                     	<?php $_from = $this->_tpl_vars['banners_status_count']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['status_row']):
?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 1): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_ACTIVE_BANNERS_COUNT']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['active_status']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url('admin/banners/search/search_status/1'); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW']; ?>
</a>
	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 2): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_BLOCKED_BANNERS_COUNT']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['blocked_status']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url('admin/banners/search/search_status/2'); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW']; ?>
</a>
	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 3): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_SUSPENDED_BANNERS_COUNT']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['suspended_status']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url('admin/banners/search/search_status/3'); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW']; ?>
</a>
	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 4): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_NOTPAID_BANNERS_COUNT']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['not_paid_status']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url('admin/banners/search/search_status/4'); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW']; ?>
</a>
	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php endforeach; endif; unset($_from); ?>
	                     </table>
                     </div>
                     <?php endif; ?>
                     
                     <?php if ($this->_tpl_vars['CI']->load->is_module_loaded('payment') && $this->_tpl_vars['content_access_obj']->isPermission('View all invoices') && $this->_tpl_vars['content_access_obj']->isPermission('View all transactions')): ?>
                     <label class="block_title"><?php echo $this->_tpl_vars['LANG_INVOICE_SUMMARY']; ?>
</label>
                     <div class="admin_option">
	                     <table>
	                     	<?php $_from = $this->_tpl_vars['invoices_status_count']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['status_row']):
?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 1): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_NOTPAID_INVOICES']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['not_paid_status']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url('admin/payment/invoices/search/search_status/1'); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW']; ?>
</a>
	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 2): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_PAID_INVOICES']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['paid_status']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url('admin/payment/invoices/search/search_status/2'); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW']; ?>
</a>
	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php endforeach; endif; unset($_from); ?>
	                     </table>
                     </div>
                     <label class="block_title"><?php echo $this->_tpl_vars['LANG_TRANSACTION_SUMMARY']; ?>
</label>
                     <div class="admin_option">
	                     <table>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_COMPLETED_TRANSACTIONS']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['transactions_count']; ?>

	                     		</td>
	                     	</tr>
	                     	<?php $_from = $this->_tpl_vars['transactions_summary']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['summary']):
?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_TOTAL_SOLD']; ?>
 (<?php echo $this->_tpl_vars['summary']['mc_currency']; ?>
):
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php if ($this->_tpl_vars['summary']['transactions_amount'] != null): ?>
	                     				<?php echo $this->_tpl_vars['summary']['transactions_amount']; ?>

	                     			<?php else: ?>
	                     				0
	                     			<?php endif; ?>
	                     		</td>
	                     	</tr>
	                     	<?php endforeach; endif; unset($_from); ?>
	                     	<?php $_from = $this->_tpl_vars['transactions_summary']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['summary']):
?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_TOTAL_FEES']; ?>
 (<?php echo $this->_tpl_vars['summary']['mc_currency']; ?>
):
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php if ($this->_tpl_vars['summary']['transactions_fee_amount'] != null): ?>
	                     				<?php echo $this->_tpl_vars['summary']['transactions_fee_amount']; ?>

	                     			<?php else: ?>
	                     				0
	                     			<?php endif; ?>
	                     		</td>
	                     	</tr>
	                     	<?php endforeach; endif; unset($_from); ?>
	                     </table>
                     </div>
                     <?php endif; ?>
                     
                     <?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage all reviews') || $this->_tpl_vars['content_access_obj']->isPermission('Manage coupons')): ?>
                     <label class="block_title"><?php echo $this->_tpl_vars['LANG_SUMMARY_MISCELLANEOUS']; ?>
</label>
                     <div class="admin_option">
                     	<?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage all reviews')): ?>
                     	<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/reviews/listings/search/orderby/r.date_added/direction/desc/"); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW_LATEST_REVIEWS']; ?>
</a>
                     	<br />
                     	<?php endif; ?>
                     	<?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage coupons')): ?>
                     	<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/coupons/search/orderby/dcu.usage_date/direction/desc/"); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW_LATEST_COUPONS']; ?>
</a>
                     	<?php endif; ?>
                     </div>
                     <?php endif; ?>
                     
                     
                     <!--<div class="px10"></div>
                     <div class="px10"></div>-->
                     <h3><?php echo $this->_tpl_vars['LANG_MY_SUMMARY_INFO']; ?>
</h3>
                     
                     <?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage self listings')): ?>
                     <label class="block_title"><?php echo $this->_tpl_vars['LANG_MY_LISTINGS_SUMMARY']; ?>
</label>
                     <div class="admin_option">
	                     <table>
	                     	<?php $_from = $this->_tpl_vars['my_listings_status_count']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['status_row']):
?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 1): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_ACTIVE_LISTINGS']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['active_status']; ?>

	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 2): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_BLOCKED_LISTINGS']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['blocked_status']; ?>

	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 3): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_SUSPENDED_LISTINGS']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['suspended_status']; ?>

	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 4): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_UNAPPROVED_LISTINGS']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['unapproved_status']; ?>

	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 5): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_NOTPAID_LISTINGS']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['not_paid_status']; ?>

	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php endforeach; endif; unset($_from); ?>
	                     </table>
                     </div>
                     <?php endif; ?>

                     <?php if ($this->_tpl_vars['CI']->load->is_module_loaded('banners') && $this->_tpl_vars['content_access_obj']->isPermission('Manage self banners')): ?>
                     <label class="block_title"><?php echo $this->_tpl_vars['LANG_MY_BANNERS_SUMMARY']; ?>
</label>
                     <div class="admin_option">
	                     <table>
	                     	<?php $_from = $this->_tpl_vars['my_banners_status_count']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['status_row']):
?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 1): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_ACTIVE_BANNERS_COUNT']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['active_status']; ?>

	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 2): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_BLOCKED_BANNERS_COUNT']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['blocked_status']; ?>

	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 3): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_SUSPENDED_BANNERS_COUNT']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['suspended_status']; ?>

	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 4): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_NOTPAID_BANNERS_COUNT']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['not_paid_status']; ?>

	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php endforeach; endif; unset($_from); ?>
	                     </table>
                     </div>
                     <?php endif; ?>
                     
                     <?php if ($this->_tpl_vars['CI']->load->is_module_loaded('payment') && $this->_tpl_vars['content_access_obj']->isPermission('View self invoices') && $this->_tpl_vars['content_access_obj']->isPermission('View self transactions')): ?>
                     <label class="block_title"><?php echo $this->_tpl_vars['LANG_MY_INVOICE_SUMMARY']; ?>
</label>
                     <div class="admin_option">
	                     <table>
	                     	<?php $_from = $this->_tpl_vars['my_invoices_status_count']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['status_row']):
?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 1): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_NOTPAID_INVOICES']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['not_paid_status']; ?>

	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php if ($this->_tpl_vars['status_row']['status'] == 2): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_PAID_INVOICES']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['status_row']['paid_status']; ?>

	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php endforeach; endif; unset($_from); ?>
	                     </table>
                     </div>
                     <label class="block_title"><?php echo $this->_tpl_vars['LANG_MY_TRANSACTION_SUMMARY']; ?>
</label>
                     <div class="admin_option">
	                     <table>
	                     	<?php if ($this->_tpl_vars['summary']['transactions_amount']): ?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_COMPLETED_TRANSACTIONS']; ?>
:
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php echo $this->_tpl_vars['my_transactions_count']; ?>

	                     		</td>
	                     	</tr>
	                     	<?php endif; ?>
	                     	<?php $_from = $this->_tpl_vars['my_transactions_summary']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['summary']):
?>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			<?php echo $this->_tpl_vars['LANG_TOTAL_PAID']; ?>
 (<?php echo $this->_tpl_vars['summary']['mc_currency']; ?>
):
	                     		</td>
	                     		<td class="table_right_side">
	                     			<?php if ($this->_tpl_vars['summary']['transactions_amount'] != null): ?>
	                     				<?php echo $this->_tpl_vars['summary']['transactions_amount']; ?>

	                     			<?php else: ?>
	                     				0
	                     			<?php endif; ?>
	                     		</td>
	                     	</tr>
	                     	<?php endforeach; endif; unset($_from); ?>
	                     </table>
                     </div>
                     <?php endif; ?>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>