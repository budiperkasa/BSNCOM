<?php /* Smarty version 2.6.26, created on 2012-02-08 07:41:16
         compiled from users/admin_user_options_menu.tpl */ ?>
<?php $this->assign('user_id', $this->_tpl_vars['user']->id); ?>
<?php $this->assign('user_login', $this->_tpl_vars['user']->login); ?>

				<div class="admin_top_menu_cell">
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/create"); ?>
" title="<?php echo $this->_tpl_vars['LANG_CREATE_USER_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/user_add.png" /></a>
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/create"); ?>
"><?php echo $this->_tpl_vars['LANG_CREATE_USER_OPTION']; ?>
</a>
				</div>

				<div class="admin_top_menu_cell">
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/search/use_advanced/true/search_owner/".($this->_tpl_vars['user_login'])."/"); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_LISTINGS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_copy.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/search/use_advanced/true/search_owner/".($this->_tpl_vars['user_login'])."/"); ?>
"><?php echo $this->_tpl_vars['LANG_USER_LISTINGS_OPTION']; ?>
</a>
				</div>
				
				<?php if ($this->_tpl_vars['CI']->load->is_module_loaded('banners') && $this->_tpl_vars['content_access_obj']->isPermission('Manage all banners')): ?>
				<div class="admin_top_menu_cell">
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/banners/search/search_owner/".($this->_tpl_vars['user_login'])."/"); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_BANNERS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/banners.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/banners/search/search_owner/".($this->_tpl_vars['user_login'])."/"); ?>
"><?php echo $this->_tpl_vars['LANG_USER_BANNERS_OPTION']; ?>
</a>
				</div>
				<?php endif; ?>
				
				<?php if ($this->_tpl_vars['CI']->load->is_module_loaded('payment') && $this->_tpl_vars['content_access_obj']->isPermission('View all invoices')): ?>
				<div class="admin_top_menu_cell">
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/payment/invoices/search/search_owner/".($this->_tpl_vars['user_login'])."/"); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_INVOICES_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/invoices.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/payment/invoices/search/search_owner/".($this->_tpl_vars['user_login'])."/"); ?>
"><?php echo $this->_tpl_vars['LANG_USER_INVOICES_OPTION']; ?>
</a>
				</div>
				<?php endif; ?>
				
				<?php if ($this->_tpl_vars['CI']->load->is_module_loaded('payment') && $this->_tpl_vars['content_access_obj']->isPermission('View all transactions')): ?>
				<div class="admin_top_menu_cell">
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/payment/transactions/search/search_owner/".($this->_tpl_vars['user_login'])."/"); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_TRANSACTIONS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/transactions.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/payment/transactions/search/search_owner/".($this->_tpl_vars['user_login'])."/"); ?>
"><?php echo $this->_tpl_vars['LANG_USER_TRANSACTIONS_OPTION']; ?>
</a>
				</div>
				<?php endif; ?>
				
				<?php if ($this->_tpl_vars['CI']->load->is_module_loaded('packages') && $this->_tpl_vars['content_access_obj']->isPermission('Manage packages')): ?>
				<div class="admin_top_menu_cell">
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/packages/search/search_login/".($this->_tpl_vars['user_login'])."/"); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_PACKAGES_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/packages.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/packages/search/search_login/".($this->_tpl_vars['user_login'])."/"); ?>
"><?php echo $this->_tpl_vars['LANG_USER_PACKAGES_OPTION']; ?>
</a>
				</div>
				<?php endif; ?>
					
				<?php if ($this->_tpl_vars['CI']->load->is_module_loaded('discount_coupons') && $this->_tpl_vars['content_access_obj']->isPermission('Manage coupons')): ?>
				<div class="admin_top_menu_cell">
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/coupons/send/user_id/".($this->_tpl_vars['user_id'])); ?>
" class="nyroModal noborder" title="<?php echo $this->_tpl_vars['LANG_SEND_COUPON_TO_USER']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/tag_blue_add.png" /></a>
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/coupons/send/user_id/".($this->_tpl_vars['user_id'])); ?>
" class="nyroModal noborder" title="<?php echo $this->_tpl_vars['LANG_SEND_COUPON_TO_USER']; ?>
"><?php echo $this->_tpl_vars['LANG_SEND_COUPON_TO_USER']; ?>
</a>
				</div>
				
				<div class="admin_top_menu_cell">
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/coupons/search/search_login/".($this->_tpl_vars['user_login'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_COUPONS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/coupons.png" /></a>
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/coupons/search/search_login/".($this->_tpl_vars['user_login'])); ?>
"><?php echo $this->_tpl_vars['LANG_USER_COUPONS_OPTION']; ?>
</a>
				</div>
				<?php endif; ?>

				<?php if ($this->_tpl_vars['user']->id != 1): ?>
				<div class="admin_top_menu_cell">
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/profile/".($this->_tpl_vars['user_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_EDIT_PROFILE_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/user_edit.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/profile/".($this->_tpl_vars['user_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_USER_EDIT_PROFILE_OPTION']; ?>
</a>
				</div>

				<div class="admin_top_menu_cell">
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/change_group/".($this->_tpl_vars['user_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_CHANGE_GROUP_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/vcard.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/change_group/".($this->_tpl_vars['user_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_USER_CHANGE_GROUP_OPTION']; ?>
</a>
				</div>
				
				<div class="admin_top_menu_cell">
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/change_status/".($this->_tpl_vars['user_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_CHANGE_STATUS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/user_red.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/change_status/".($this->_tpl_vars['user_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_USER_CHANGE_STATUS_OPTION']; ?>
</a>
				</div>

				<div class="admin_top_menu_cell">
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/delete/".($this->_tpl_vars['user_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_DELETE_PROFILE_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/user_delete.png"></a>
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/delete/".($this->_tpl_vars['user_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_USER_DELETE_PROFILE_OPTION']; ?>
</a>
				</div>
				<?php endif; ?>

				<div class="admin_top_menu_cell">
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("email/send/user_id/".($this->_tpl_vars['user_id'])); ?>
" class="nyroModal noborder" title="<?php echo $this->_tpl_vars['LANG_SEND_EMAIL_TO_USER']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/user_go.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("email/send/user_id/".($this->_tpl_vars['user_id'])); ?>
" class="nyroModal noborder" title="<?php echo $this->_tpl_vars['LANG_SEND_EMAIL_TO_USER']; ?>
"><?php echo $this->_tpl_vars['LANG_SEND_EMAIL_TO_USER']; ?>
</a>
				</div>

				<div class="clear_float"></div>
				<div class="px10"></div>