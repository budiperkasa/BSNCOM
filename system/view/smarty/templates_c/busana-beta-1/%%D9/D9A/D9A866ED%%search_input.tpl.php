<?php /* Smarty version 2.6.26, created on 2012-02-06 08:03:48
         compiled from content_fields/search/datetime/search_input.tpl */ ?>
<?php $this->assign('random_id', $this->_tpl_vars['VH']->genRandomString()); ?>

						<script language="javascript" type="text/javascript">
								var <?php echo $this->_tpl_vars['random_id']; ?>
_mode = '<?php echo $this->_tpl_vars['mode']; ?>
';

				                    $(document).ready( function() {
				                        $("#search_form").submit( function() {
				                        	if (<?php echo $this->_tpl_vars['random_id']; ?>
_mode == 'single') {
					                        	if ($("#search_tmstmp_<?php echo $this->_tpl_vars['field']->seo_name; ?>
").val() != '')
													global_js_url = global_js_url + "<?php echo $this->_tpl_vars['field_index']; ?>
" + '/' + $("#search_tmstmp_<?php echo $this->_tpl_vars['field']->seo_name; ?>
").val() + '/';
											}
											if (<?php echo $this->_tpl_vars['random_id']; ?>
_mode == 'range') {
												if ($("#from_tmstmp_<?php echo $this->_tpl_vars['field']->seo_name; ?>
").val() != '')
													global_js_url = global_js_url + "from_tmstmp_<?php echo $this->_tpl_vars['field']->seo_name; ?>
" + '/' + $("#from_tmstmp_<?php echo $this->_tpl_vars['field']->seo_name; ?>
").val() + '/';
												if ($("#to_tmstmp_<?php echo $this->_tpl_vars['field']->seo_name; ?>
").val() != '')
													global_js_url = global_js_url + "to_tmstmp_<?php echo $this->_tpl_vars['field']->seo_name; ?>
" + '/' + $("#to_tmstmp_<?php echo $this->_tpl_vars['field']->seo_name; ?>
").val() + '/';
											}
											window.location.href = global_js_url;
											return false;
										});
									});
							</script>

<?php if ($this->_tpl_vars['field']->frontend_name): ?>
	<?php $this->assign('field_name', $this->_tpl_vars['field']->frontend_name); ?>
<?php else: ?>
	<?php $this->assign('field_name', $this->_tpl_vars['field']->name); ?>
<?php endif; ?>

							<?php $this->assign('field_title', $this->_tpl_vars['field_name']); ?>
							<?php $this->assign('search_mode', $this->_tpl_vars['mode']); ?>
							<?php $this->assign('date_var_name', $this->_tpl_vars['field']->seo_name); ?>
							<?php $this->assign('single_date_var_value', $this->_tpl_vars['creation_date']); ?>
							<?php $this->assign('single_date_var_value_tmstmp', $this->_tpl_vars['date_tmstmp']); ?>
							<?php $this->assign('from_date_var_value', $this->_tpl_vars['from_date']); ?>
							<?php $this->assign('from_date_var_value_tmstmp', $this->_tpl_vars['from_date_tmstmp']); ?>
							<?php $this->assign('to_date_var_value', $this->_tpl_vars['to_date']); ?>
							<?php $this->assign('to_date_var_value_tmstmp', $this->_tpl_vars['to_date_tmstmp']); ?>
							<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content_fields/common_date_range_search.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>