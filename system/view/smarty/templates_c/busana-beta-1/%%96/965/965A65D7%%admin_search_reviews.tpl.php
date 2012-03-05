<?php /* Smarty version 2.6.26, created on 2012-02-07 09:00:58
         compiled from ratings_reviews/admin_search_reviews.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'ratings_reviews/admin_search_reviews.tpl', 104, false),array('modifier', 'date_format', 'ratings_reviews/admin_search_reviews.tpl', 145, false),array('function', 'asc_desc_insert', 'ratings_reviews/admin_search_reviews.tpl', 112, false),)), $this); ?>
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
					        	if ($("#search_tmstmp_date_added").val() != '')
									global_js_url = global_js_url + "search_date_added" + '/' + $("#search_tmstmp_date_added").val() + '/';
							}
							if (<?php echo $this->_tpl_vars['random_id']; ?>
_mode == 'range') {
								if ($("#from_tmstmp_date_added").val() != '')
									global_js_url = global_js_url + "search_from_date_added" + '/' + $("#from_tmstmp_date_added").val() + '/';
								if ($("#to_tmstmp_date_added").val() != '')
									global_js_url = global_js_url + "search_to_date_added" + '/' + $("#to_tmstmp_date_added").val() + '/';
							}

							if ($("#search_login").val() != '')
		                		global_js_url = global_js_url + 'search_login/' + $("#search_login").val() + '/';
		                	global_js_url = global_js_url + "search_anonyms" + '/' + $("input[name=search_anonyms]:checked").val() + '/';
		                	if ($("#search_status").val() != -1)
		                		global_js_url = global_js_url + 'search_status/' + $("#search_status").val() + '/';

							window.location.href = global_js_url;
							return false;
						});
					});

					function submit_reviews_form()
	            	{
	              		$("#reviews_form").attr("action", '<?php echo $this->_tpl_vars['VH']->site_url('admin/reviews/'); ?>
' + action_cmd + '/');
	               		return true;
	            	}
                </script>

                <div class="content">
                     <h3><?php echo $this->_tpl_vars['LANG_SEARCH_REVIEWS_TITLE']; ?>
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
	                     		<label><input type="radio" name="search_anonyms" value="anonyms" <?php if ($this->_tpl_vars['args']['search_anonyms'] == 'anonyms'): ?>checked<?php endif; ?>/> <?php echo $this->_tpl_vars['LANG_SEARCH_ONLY_ANONYMS']; ?>
</label>
	                     		<label><input type="radio" name="search_anonyms" value="all" <?php if ($this->_tpl_vars['args']['search_anonyms'] == 'all' || $this->_tpl_vars['args']['search_anonyms'] == null): ?>checked<?php endif; ?>/> <?php echo $this->_tpl_vars['LANG_SEARCH_WITH_ANONYMS']; ?>
</label>
	                     	</div>
	                     	<div class="search_item">
	                     		<label><?php echo $this->_tpl_vars['LANG_SEARCH_STATUS']; ?>
:</label>
	                     		<select id="search_status">
	                     			<option value="-1">- - - <?php echo $this->_tpl_vars['LANG_SEARCH_ANY_STATUS']; ?>
 - - -</option>
	                     			<option value="1" <?php if ($this->_tpl_vars['args']['search_status'] == 1): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG_STATUS_ACTIVE']; ?>
</option>
	                     			<option value="2" <?php if ($this->_tpl_vars['args']['search_status'] == 2): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG_STATUS_SPAM']; ?>
</option>
	                     		</select>
	                     	</div>
	                     	
	                     	<?php $this->assign('field_title', $this->_tpl_vars['LANG_SEARCH_DATE_ADDED']); ?>
	                     	<?php $this->assign('search_mode', $this->_tpl_vars['mode']); ?>
	                     	<?php $this->assign('date_var_name', 'date_added'); ?>
	                     	<?php $this->assign('single_date_var_value', $this->_tpl_vars['date_added']); ?>
	                     	<?php $this->assign('single_date_var_value_tmstmp', $this->_tpl_vars['date_added_tmstmp']); ?>
	                     	<?php $this->assign('from_date_var_value', $this->_tpl_vars['from_date_added']); ?>
	                     	<?php $this->assign('from_date_var_value_tmstmp', $this->_tpl_vars['from_date_added_tmstmp']); ?>
	                     	<?php $this->assign('to_date_var_value', $this->_tpl_vars['to_date_added']); ?>
	                     	<?php $this->assign('to_date_var_value_tmstmp', $this->_tpl_vars['to_date_added_tmstmp']); ?>
	                     	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "content_fields/common_date_range_search.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                     	</div>
                     	<div class="clear_float"></div>
                     	<div class="search_item_button">
	                    	<input type="submit" class="button search_button" id="process_search" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SEARCH_REVIEWS']; ?>
">
	                    </div>
                     </form>
                     
                     <div class="search_results_title">
                     	<?php echo $this->_tpl_vars['LANG_SEARCH_REVIEWS_RESULT_1']; ?>
 (<?php echo $this->_tpl_vars['reviews_count']; ?>
 <?php echo $this->_tpl_vars['LANG_SEARCH_REVIEWS_RESULT_2']; ?>
):
                     </div>

                     <?php if (count($this->_tpl_vars['reviews']) > 0): ?>
                     <form id="reviews_form" action="" method="post" onSubmit="submit_reviews_form();">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th width="1"><input type="checkbox"></th>
                         <th><?php echo $this->_tpl_vars['LANG_USERS_LOGIN_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_REVIEW_STATUS_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_REVIEW_BODY_TH']; ?>
</th>
                         <th><?php echo smarty_function_asc_desc_insert(array('base_url' => $this->_tpl_vars['search_url'],'orderby' => 'r.date_added','orderby_query' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction'],'title' => $this->_tpl_vars['LANG_PLACEMENT_DATE_TH']), $this);?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_OPTIONS_TH']; ?>
</th>
                       </tr>
                       <?php $_from = $this->_tpl_vars['reviews']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['review']):
?>
						<?php $this->assign('user', $this->_tpl_vars['review']->getUser()); ?>
						<?php $this->assign('object', $this->_tpl_vars['review']->getObject()); ?>
						<?php $this->assign('rating', $this->_tpl_vars['review']->setRating()); ?>
						<?php $this->assign('review_id', $this->_tpl_vars['review']->id); ?>
						<?php $this->assign('review_object_id', $this->_tpl_vars['review']->object_id); ?>
                       <tr>
                         <td>
                    	  	<input type="checkbox" name="cb_<?php echo $this->_tpl_vars['review']->id; ?>
" value="<?php echo $this->_tpl_vars['review']->id; ?>
">
                    	 </td>
                         <td>
                         	<?php if ($this->_tpl_vars['review']->user_id): ?>
	                         	<?php $this->assign('review_user_id', $this->_tpl_vars['review']->user->id); ?>
								<?php if ($this->_tpl_vars['review_user_id'] != 1 && $this->_tpl_vars['content_access_obj']->isPermission('Manage users')): ?>
									<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/view/".($this->_tpl_vars['review_user_id'])); ?>
"><?php echo $this->_tpl_vars['review']->user->login; ?>
</a>
								<?php else: ?>
									<?php echo $this->_tpl_vars['review']->user->login; ?>

								<?php endif; ?>
							<?php else: ?>
								<?php echo $this->_tpl_vars['LANG_ANONYM']; ?>
: <?php echo $this->_tpl_vars['review']->anonym_name; ?>
 (<?php echo $this->_tpl_vars['review']->ip; ?>
)
							<?php endif; ?>
                         </td>
                         <td>
                         	<?php if ($this->_tpl_vars['review']->status == 1): ?><span class="status_active"><?php echo $this->_tpl_vars['LANG_STATUS_ACTIVE']; ?>
</span><?php endif; ?>
							<?php if ($this->_tpl_vars['review']->status == 2): ?><span class="status_spam"><?php echo $this->_tpl_vars['LANG_STATUS_SPAM']; ?>
</span><?php endif; ?>
                         </td>
                         <td>
                         	<?php echo $this->_tpl_vars['review']->review; ?>

                         </td>
                         <td>
                            <?php echo ((is_array($_tmp=$this->_tpl_vars['review']->date_added)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %T") : smarty_modifier_date_format($_tmp, "%D %T")); ?>
&nbsp;
                         </td>
                         <td>
                         	<nobr>
                         	<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/reviews/edit/".($this->_tpl_vars['review_id'])); ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_edit.png" /></a>
                         	<?php if ($this->_tpl_vars['review']->object->isObject()): ?>
                         	<a href="<?php echo $this->_tpl_vars['review']->object->getObjectReviewsUrl(); ?>
" title="<?php echo $this->_tpl_vars['LANG_REVIEWS_VIEW_ALL_REVIEWS']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_copy.png" /></a>
                         	<?php endif; ?>
                         	</nobr>
                         </td>
                       </tr>
                       <?php endforeach; endif; unset($_from); ?>
                     </table>
                     <?php echo $this->_tpl_vars['LANG_WITH_SELECTED']; ?>
:
	                 <select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_reviews_form(); this.form.submit()">
	                 	<option value=""><?php echo $this->_tpl_vars['LANG_CHOOSE_ACTION']; ?>
</option>
	                 	<option value="delete"><?php echo $this->_tpl_vars['LANG_BUTTON_DELETE_REVIEWS']; ?>
</option>
	                 	<option value="spam"><?php echo $this->_tpl_vars['LANG_BUTTON_SPAM_REVIEWS']; ?>
</option>
	                 	<option value="active"><?php echo $this->_tpl_vars['LANG_BUTTON_ACTIVE_REVIEWS']; ?>
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