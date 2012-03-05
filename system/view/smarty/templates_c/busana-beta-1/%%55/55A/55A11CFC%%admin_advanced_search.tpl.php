<?php /* Smarty version 2.6.26, created on 2012-02-06 04:06:07
         compiled from listings/admin_advanced_search.tpl */ ?>
				<script language="javascript" type="text/javascript">
						<?php $this->assign('random_id', $this->_tpl_vars['VH']->genRandomString()); ?>
						var <?php echo $this->_tpl_vars['random_id']; ?>
_mode = '<?php echo $this->_tpl_vars['mode']; ?>
';

						$(document).ready( function() {
				            <?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage all listings')): ?>
				            $('#search_owner').autocomplete({
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
							<?php endif; ?>

				            $("#search_form").submit( function() {
				            	<?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage all listings')): ?>
				            		if (use_advanced) {
						               	if (<?php echo $this->_tpl_vars['random_id']; ?>
_mode == 'single') {
						                   	if ($("#search_tmstmp_creation_date").val() != '')
												global_js_url = global_js_url + "search_creation_date" + '/' + $("#search_tmstmp_creation_date").val() + '/';
										}
										if (<?php echo $this->_tpl_vars['random_id']; ?>
_mode == 'range') {
											if ($("#from_tmstmp_creation_date").val() != '')
												global_js_url = global_js_url + "search_from_creation_date" + '/' + $("#from_tmstmp_creation_date").val() + '/';
											if ($("#to_tmstmp_creation_date").val() != '')
												global_js_url = global_js_url + "search_to_creation_date" + '/' + $("#to_tmstmp_creation_date").val() + '/';
										}
										if ($("#search_owner").val() != '')
											global_js_url = global_js_url + 'search_owner/' + urlencode($("#search_owner").val()) + '/';
										if ($("#search_status").val() != -1)
											global_js_url = global_js_url + 'search_status/' + $("#search_status").val() + '/';
									}
								<?php endif; ?>
								window.location.href = global_js_url;
								return false;
							});
						});
				</script>

				<?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage all listings')): ?>
				<div class="search_item">
					<label><?php echo $this->_tpl_vars['LANG_SEARCH_BY_OWNER']; ?>
:</label>
					<input type="text" id="search_owner" value="<?php echo $this->_tpl_vars['args']['search_owner']; ?>
" style="width: 205px;" />
				</div>
				<div class="search_item">
					<label><?php echo $this->_tpl_vars['LANG_SEARCH_BY_STATUS']; ?>
:</label>
					<select id="search_status" style="min-width: 100px;">
						<option value="-1">- - - <?php echo $this->_tpl_vars['LANG_STATUS_ANY']; ?>
 - - -</option>
						<option value="1" <?php if ($this->_tpl_vars['args']['search_status'] == 1): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG_STATUS_ACTIVE']; ?>
</option>
						<option value="2" <?php if ($this->_tpl_vars['args']['search_status'] == 2): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG_STATUS_BLOCKED']; ?>
</option>
						<option value="3" <?php if ($this->_tpl_vars['args']['search_status'] == 3): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG_STATUS_SUSPENDED']; ?>
</option>
						<option value="4" <?php if ($this->_tpl_vars['args']['search_status'] == 4): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG_STATUS_UNAPPROVED']; ?>
</option>
						<option value="5" <?php if ($this->_tpl_vars['args']['search_status'] == 5): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG_STATUS_NOTPAID']; ?>
</option>
					</select>
				</div>
				<?php $this->assign('field_title', $this->_tpl_vars['LANG_SEARCH_BY_CREATION_DATE']); ?>
				<?php $this->assign('search_mode', $this->_tpl_vars['mode']); ?>
				<?php $this->assign('date_var_name', 'creation_date'); ?>
				<?php $this->assign('single_date_var_value', $this->_tpl_vars['creation_date']); ?>
				<?php $this->assign('single_date_var_value_tmstmp', $this->_tpl_vars['creation_date_tmstmp']); ?>
				<?php $this->assign('from_date_var_value', $this->_tpl_vars['from_creation_date']); ?>
				<?php $this->assign('from_date_var_value_tmstmp', $this->_tpl_vars['from_creation_date_tmstmp']); ?>
				<?php $this->assign('to_date_var_value', $this->_tpl_vars['to_creation_date']); ?>
				<?php $this->assign('to_date_var_value_tmstmp', $this->_tpl_vars['to_creation_date_tmstmp']); ?>
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content_fields/common_date_range_search.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				<div class="clear_float"></div>
				<?php endif; ?>
				
				<?php echo $this->_tpl_vars['search_fields']->inputMode($this->_tpl_vars['args']); ?>