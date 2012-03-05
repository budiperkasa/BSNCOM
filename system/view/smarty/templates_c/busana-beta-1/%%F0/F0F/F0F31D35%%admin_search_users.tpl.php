<?php /* Smarty version 2.6.26, created on 2012-02-06 03:58:49
         compiled from users/admin_search_users.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'users/admin_search_users.tpl', 123, false),array('modifier', 'date_format', 'users/admin_search_users.tpl', 178, false),array('function', 'asc_desc_insert', 'users/admin_search_users.tpl', 128, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

				<script language="javascript" type="text/javascript">
					var global_js_url = '<?php echo $this->_tpl_vars['base_url']; ?>
';
					
					<?php $this->assign('random_id', $this->_tpl_vars['VH']->genRandomString()); ?>
					var <?php echo $this->_tpl_vars['random_id']; ?>
_mode = '<?php echo $this->_tpl_vars['mode']; ?>
';
					
					var action_cmd;

					$(document).ready( function() {
				        $('#search_login').autocomplete({
							source: function(request, response) {
								$.post("<?php echo $this->_tpl_vars['VH']->site_url('admin/users/ajax_autocomplete_request/'); ?>
", {query: request.term}, function(data) {
									response($.map(data.suggestions, function(item) {
										return {
											label: item,
											value: item
										};
									}));
								}, "json");
							},
							minLength: 2,
							delay: 500,
							select: function(event, ui) {
								$(this).val(ui.item.label);
								return false;
							}
						});

				         $("#search_form").submit( function() {
				           	if (<?php echo $this->_tpl_vars['random_id']; ?>
_mode == 'single') {
					        	if ($("#search_tmstmp_last_visit_date").val() != '')
									global_js_url = global_js_url + "search_last_visit_date" + '/' + $("#search_tmstmp_last_visit_date").val() + '/';
							}
							if (<?php echo $this->_tpl_vars['random_id']; ?>
_mode == 'range') {
								if ($("#from_tmstmp_last_visit_date").val() != '')
									global_js_url = global_js_url + "search_from_last_visit_date" + '/' + $("#from_tmstmp_last_visit_date").val() + '/';
								if ($("#to_tmstmp_last_visit_date").val() != '')
									global_js_url = global_js_url + "search_to_last_visit_date" + '/' + $("#to_tmstmp_last_visit_date").val() + '/';
							}

							if ($("#search_login").val() != '')
		                		global_js_url = global_js_url + 'search_login/' + $("#search_login").val() + '/';
		                	if ($("#search_email").val() != '')
		                		global_js_url = global_js_url + 'search_email/' + $("#search_email").val() + '/';
		                	if ($("#groups").val() != -1)
		                		global_js_url = global_js_url + 'search_group/' + $("#groups").val() + '/';
		                	if ($("#search_status").val() != -1)
		                		global_js_url = global_js_url + 'search_status/' + $("#search_status").val() + '/';

							window.location.href = global_js_url;
							return false;
						});
					});

					function submit_users_form()
	            	{
	              		$("#users_form").attr("action", '<?php echo $this->_tpl_vars['VH']->site_url('admin/users'); ?>
' + action_cmd + '/');
	               		return true;
	            	}
                </script>

                <div class="content">
                     <h3><?php echo $this->_tpl_vars['LANG_SEARCH_USERS']; ?>
</h3>
                     
                     <form id="search_form" action="" method="post">
                     	<div class="search_block">
	                     	<div class="search_item">
	                     		<label><?php echo $this->_tpl_vars['LANG_SEARCH_LOGIN']; ?>
:</label>
	                     		<input type="text" id="search_login" value="<?php echo $this->_tpl_vars['args']['search_login']; ?>
" style="width: 205px;" />
	                     	</div>
	                     	<div class="search_item">
	                     		<label><?php echo $this->_tpl_vars['LANG_SEARCH_EMAIL']; ?>
:</label>
	                     		<input type="text" id="search_email" size="25" value="<?php echo $this->_tpl_vars['args']['search_email']; ?>
" style="width: 205px;" />
	                     	</div>
	                     	<div class="search_item">
	                     		<label><?php echo $this->_tpl_vars['LANG_SEARCH_STATUS']; ?>
:</label>
	                     		<select id="search_status">
	                     			<option value="-1">- - - <?php echo $this->_tpl_vars['LANG_SEARCH_ANY_STATUS']; ?>
 - - -</option>
	                     			<option value="1" <?php if ($this->_tpl_vars['args']['search_status'] == 1): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG_USER_STATUS_UNVERIFIED']; ?>
</option>
	                     			<option value="2" <?php if ($this->_tpl_vars['args']['search_status'] == 2): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG_USER_STATUS_ACTIVE']; ?>
</option>
	                     			<option value="3" <?php if ($this->_tpl_vars['args']['search_status'] == 3): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG_USER_STATUS_BLOCKED']; ?>
</option>
	                     		</select>
	                     	</div>
	                     	<div class="search_item">
	                     		<label><?php echo $this->_tpl_vars['LANG_SEARCH_GROUP']; ?>
:</label>
	                     		<select id="groups" name="search_group">
	                     			<option value="-1">- - - <?php echo $this->_tpl_vars['LANG_SEARCH_ANY_GROUP']; ?>
 - - -</option>
	                     			<?php $_from = $this->_tpl_vars['groups_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['groups_item']):
?>
	                     			<option value="<?php echo $this->_tpl_vars['groups_item']->id; ?>
" <?php if ($this->_tpl_vars['args']['search_group'] == $this->_tpl_vars['groups_item']->id): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['groups_item']->name; ?>
</option>
	                     			<?php endforeach; endif; unset($_from); ?>
				                </select>
	                     	</div>
	                     	
	                     	<?php $this->assign('field_title', $this->_tpl_vars['LANG_SEARCH_LASTVISIT']); ?>
	                     	<?php $this->assign('search_mode', $this->_tpl_vars['mode']); ?>
	                     	<?php $this->assign('date_var_name', 'last_visit_date'); ?>
	                     	<?php $this->assign('single_date_var_value', $this->_tpl_vars['last_visit_date']); ?>
	                     	<?php $this->assign('single_date_var_value_tmstmp', $this->_tpl_vars['last_visit_date_tmstmp']); ?>
	                     	<?php $this->assign('from_date_var_value', $this->_tpl_vars['from_last_visit_date']); ?>
	                     	<?php $this->assign('from_date_var_value_tmstmp', $this->_tpl_vars['from_last_visit_date_tmstmp']); ?>
	                     	<?php $this->assign('to_date_var_value', $this->_tpl_vars['to_last_visit_date']); ?>
	                     	<?php $this->assign('to_date_var_value_tmstmp', $this->_tpl_vars['to_last_visit_date_tmstmp']); ?>
	                     	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content_fields/common_date_range_search.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                     	</div>
                     	<div class="clear_float"></div>
                     	<div class="search_item_button">
	                    	<input type="submit" class="button search_button" id="process_search" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SEARCH_USERS']; ?>
">
	                    </div>
                     </form>
                     
                     <div class="admin_top_menu_cell">
	                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/create"); ?>
" title="<?php echo $this->_tpl_vars['LANG_CREATE_USER_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/user_add.png" /></a>
	                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/create"); ?>
"><?php echo $this->_tpl_vars['LANG_CREATE_USER_OPTION']; ?>
</a>
					 </div>
					 <div class="clear_float"></div>
                     
                     <div class="search_results_title">
                     	<?php echo $this->_tpl_vars['LANG_SEARCH_USERS_RESULT_1']; ?>
 (<?php echo $this->_tpl_vars['users_count']; ?>
 <?php echo $this->_tpl_vars['LANG_SEARCH_USERS_RESULT_2']; ?>
):
                     </div>

                     <?php if (count($this->_tpl_vars['users']) > 0): ?>
                     <form id="users_form" action="" method="post" onSubmit="submit_users_form();">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th width="1"><input type="checkbox"></th>
                         <th><?php echo smarty_function_asc_desc_insert(array('base_url' => $this->_tpl_vars['search_url'],'orderby' => 'login','orderby_query' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction'],'title' => $this->_tpl_vars['LANG_USERS_LOGIN_TH']), $this);?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_USERS_GROUP_NAME_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_USERS_STATUS_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_USERS_EMAIL_TH']; ?>
</th>
                         <th><?php echo smarty_function_asc_desc_insert(array('base_url' => $this->_tpl_vars['search_url'],'orderby' => 'last_login_date','orderby_query' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction'],'title' => $this->_tpl_vars['LANG_USERS_LAST_VISIT_TH']), $this);?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_OPTIONS_TH']; ?>
</th>
                       </tr>
                       <?php $_from = $this->_tpl_vars['users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['user']):
?>
                       <?php $this->assign('user_id', $this->_tpl_vars['user']->id); ?>
                       <?php $this->assign('user_login', $this->_tpl_vars['VH']->urlencode($this->_tpl_vars['user']->login)); ?>
                       <tr>
                         <td>
                         	<?php if ($this->_tpl_vars['user_id'] != 1): ?>
                    	  	<input type="checkbox" name="cb_<?php echo $this->_tpl_vars['user']->id; ?>
" value="<?php echo $this->_tpl_vars['user']->id; ?>
">
                    	  	<?php endif; ?>
                    	 </td>
                         <td>
                         	 <?php if ($this->_tpl_vars['user_id'] != 1): ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/view/".($this->_tpl_vars['user_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_VIEW_USER_OPTION']; ?>
"><?php echo $this->_tpl_vars['user']->login; ?>
</a>
                             <?php else: ?>
                             <?php echo $this->_tpl_vars['user']->login; ?>

                             <?php endif; ?>
                             &nbsp;
                         </td>
                         <td>
                         	 <?php if ($this->_tpl_vars['user_id'] != 1): ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/change_group/".($this->_tpl_vars['user_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_CHANGE_GROUP_OPTION']; ?>
"><?php echo $this->_tpl_vars['user']->users_group->name; ?>
</a>
                             <?php else: ?>
                             <?php echo $this->_tpl_vars['user']->users_group->name; ?>

                             <?php endif; ?>
                             &nbsp;
                         </td>
                         <td>
                         	<?php if ($this->_tpl_vars['user_id'] == 1): ?>
                         	<span class="user_first"><?php echo $this->_tpl_vars['LANG_USER_FIRST_USER']; ?>
</span>
                         	<?php else: ?>
                         	<?php if ($this->_tpl_vars['user']->status == 1): ?><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/change_status/".($this->_tpl_vars['user_id'])); ?>
" class="status_unverified" title="<?php echo $this->_tpl_vars['LANG_USER_CHANGE_STATUS_OPTION']; ?>
"><?php echo $this->_tpl_vars['LANG_USER_STATUS_UNVERIFIED']; ?>
</a><?php endif; ?>
                         	<?php if ($this->_tpl_vars['user']->status == 2): ?><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/change_status/".($this->_tpl_vars['user_id'])); ?>
" class="status_active" title="<?php echo $this->_tpl_vars['LANG_USER_CHANGE_STATUS_OPTION']; ?>
"><?php echo $this->_tpl_vars['LANG_USER_STATUS_ACTIVE']; ?>
</a><?php endif; ?>
                         	<?php if ($this->_tpl_vars['user']->status == 3): ?><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/change_status/".($this->_tpl_vars['user_id'])); ?>
" class="status_blocked" title="<?php echo $this->_tpl_vars['LANG_USER_CHANGE_STATUS_OPTION']; ?>
"><?php echo $this->_tpl_vars['LANG_USER_STATUS_BLOCKED']; ?>
</a><?php endif; ?>
                         	<?php endif; ?>
                         	&nbsp;
                         </td>
                         <td>
                         	<?php if ($this->_tpl_vars['user_id'] == 1): ?>
                         	<?php echo $this->_tpl_vars['user']->email; ?>
&nbsp;
                         	<?php else: ?>
                         	<a href="<?php echo $this->_tpl_vars['VH']->site_url("email/send/user_id/".($this->_tpl_vars['user_id'])); ?>
" class="nyroModal noborder" title="<?php echo $this->_tpl_vars['LANG_SEND_EMAIL_TO_USER']; ?>
"><?php echo $this->_tpl_vars['user']->email; ?>
</a>&nbsp;
                         	<?php endif; ?>
                         </td>
                         <td>
                            <?php echo ((is_array($_tmp=$this->_tpl_vars['user']->last_login_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %T") : smarty_modifier_date_format($_tmp, "%D %T")); ?>
&nbsp;
                         </td>
                         <td>
                         	<nobr>
                            <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/search/use_advanced/true/search_owner/".($this->_tpl_vars['user_login'])."/"); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_LISTINGS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_copy.png"></a>
                            <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/reviews/search/search_login/".($this->_tpl_vars['user_login'])."/"); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_REVIEWS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/comments.png" /></a>
                            <?php if ($this->_tpl_vars['CI']->load->is_module_loaded('banners') && $this->_tpl_vars['content_access_obj']->isPermission('Manage all banners')): ?>
                            <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/banners/search/search_owner/".($this->_tpl_vars['user_login'])."/"); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_BANNERS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/banners.png" /></a>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['CI']->load->is_module_loaded('payment') && $this->_tpl_vars['content_access_obj']->isPermission('View all invoices')): ?>
                            <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/payment/invoices/search/search_owner/".($this->_tpl_vars['user_login'])."/"); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_INVOICES_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/invoices.png" /></a>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['CI']->load->is_module_loaded('payment') && $this->_tpl_vars['content_access_obj']->isPermission('View all transactions')): ?>
                            <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/payment/transactions/search/search_owner/".($this->_tpl_vars['user_login'])."/"); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_TRANSACTIONS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/transactions.png" /></a>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['CI']->load->is_module_loaded('packages') && $this->_tpl_vars['content_access_obj']->isPermission('Manage packages')): ?>
                            <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/packages/search/search_login/".($this->_tpl_vars['user_login'])."/"); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_PACKAGES_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/packages.png" /></a>
                            <?php endif; ?>
                            <?php if ($this->_tpl_vars['CI']->load->is_module_loaded('discount_coupons') && $this->_tpl_vars['content_access_obj']->isPermission('Manage coupons')): ?>
                            <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/coupons/send/user_id/".($this->_tpl_vars['user_id'])); ?>
" class="nyroModal noborder" title="<?php echo $this->_tpl_vars['LANG_SEND_COUPON_TO_USER']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/tag_blue_add.png" /></a>
                            <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/coupons/search/search_login/".($this->_tpl_vars['user_login'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_COUPONS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/coupons.png" /></a>
                            <?php endif; ?>
                         	<?php if ($this->_tpl_vars['user_id'] != 1): ?>
                         	<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/view/".($this->_tpl_vars['user_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_VIEW_PROFILE_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page.png"></a>
                         	<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/profile/".($this->_tpl_vars['user_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_EDIT_PROFILE_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_edit.png"></a>
                         	<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/delete/".($this->_tpl_vars['user_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_DELETE_PROFILE_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/user_delete.png"></a>
                            <?php endif; ?>
                            <a href="<?php echo $this->_tpl_vars['VH']->site_url("email/send/user_id/".($this->_tpl_vars['user_id'])); ?>
" class="nyroModal noborder" title="<?php echo $this->_tpl_vars['LANG_SEND_EMAIL_TO_USER']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/user_go.png" /></a>
                            </nobr>
                         </td>
                       </tr>
                       <?php endforeach; endif; unset($_from); ?>
                     </table>
                     <?php echo $this->_tpl_vars['LANG_WITH_SELECTED']; ?>
:
	                 <select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_users_form(); this.form.submit()">
	                 	<option value=""><?php echo $this->_tpl_vars['LANG_CHOOSE_ACTION']; ?>
</option>
	                 	<option value="block"><?php echo $this->_tpl_vars['LANG_BUTTON_BLOCK_USERS']; ?>
</option>
	                 </select>
                     <?php echo $this->_tpl_vars['paginator']; ?>

                     </form>
                     <?php endif; ?>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>