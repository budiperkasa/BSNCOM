<?php /* Smarty version 2.6.26, created on 2012-02-08 07:13:03
         compiled from frontend/user_page.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'frontend/user_page.tpl', 22, false),array('function', 'render_frontend_block', 'frontend/user_page.tpl', 52, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->assign('user_id', $this->_tpl_vars['user']->id); ?>
<?php $this->assign('user_unique_id', $this->_tpl_vars['user']->getUniqueId()); ?>
<?php $this->assign('user_login', $this->_tpl_vars['user']->login); ?>

			<tr>
				<td id="search_bar" colspan="3">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/search_block.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				</td>
			</tr>
			<tr>
				<td id="left_sidebar">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/left-sidebar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				</td>
      			<td id="content_block" valign="top">
      				<div id="content_wrapper">
						<div class="breadcrumbs"><a href="<?php echo $this->_tpl_vars['VH']->index_url(); ?>
"><?php echo $this->_tpl_vars['LANG_HOME_PAGE']; ?>
</a> » <span><?php echo $this->_tpl_vars['user']->login; ?>
</span></div>

						<div id="user_header_block">
	                        <div style="float: left; width: 345px;">
	                        	<h1 class="user_login"><?php echo $this->_tpl_vars['user']->login; ?>
</h1>
	                        	<div class="listing_author"><?php echo $this->_tpl_vars['LANG_USER_REGISTERED']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['user']->registration_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %H:%M") : smarty_modifier_date_format($_tmp, "%D %H:%M")); ?>
</div>
	                        	<div class="px5"></div>

		                        <?php if ($this->_tpl_vars['user']->users_group->logo_enabled && ( $this->_tpl_vars['user']->user_logo_image || $this->_tpl_vars['user']->facebook_logo_file )): ?>
		                        <div id="user_logo">
		                        	<?php if ($this->_tpl_vars['user']->use_facebook_logo && $this->_tpl_vars['user']->facebook_logo_file): ?>
										<img src="<?php echo $this->_tpl_vars['user']->facebook_logo_file; ?>
" />
									<?php elseif ($this->_tpl_vars['user']->users_group->logo_enabled && ! $this->_tpl_vars['user']->use_facebook_logo && $this->_tpl_vars['user']->user_logo_image): ?>
										<img src="<?php echo $this->_tpl_vars['users_content']; ?>
/users_images/users_logos/<?php echo $this->_tpl_vars['user']->user_logo_image; ?>
">
									<?php endif; ?>
								</div>
								<?php endif; ?>
							</div>

							<div id="user_options_panel">
								<a class="a2a_dd" href="http://www.addtoany.com/share_save"><img src="http://static.addtoany.com/buttons/share_save_171_16.png" width="171" height="16" border="0" alt="Share/Bookmark"/></a><script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>
								<span class="user_options_panel_item print"><a href="javascript: void(0);" onClick="$.jqURL.loc('<?php echo $this->_tpl_vars['VH']->site_url("print_user/".($this->_tpl_vars['user_id'])."/"); ?>
', {w:590,h:750,wintype:'_blank'}); return false;"><?php echo $this->_tpl_vars['LANG_PRINT_PAGE']; ?>
</a></span>
								<span class="user_options_panel_item pdf"><a href="http://pdfmyurl.com/?url=<?php echo $this->_tpl_vars['VH']->site_url("users/".($this->_tpl_vars['user_unique_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_PDF_PAGE']; ?>
</a></span>
								<span class="user_options_panel_item owner"><a href="<?php echo $this->_tpl_vars['VH']->site_url("email/send/user_id/".($this->_tpl_vars['user_id'])); ?>
" class="nyroModal" title="<?php echo $this->_tpl_vars['LANG_SEND_EMAIL_TO_USER']; ?>
"><?php echo $this->_tpl_vars['LANG_SEND_EMAIL_TO_USER']; ?>
</a></span>
								<span class="user_options_panel_item all_listings"><a href="<?php echo $this->_tpl_vars['VH']->site_url("search/search_owner/".($this->_tpl_vars['user_login'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_VIEW_ALL_LISTINGS']; ?>
"><?php echo $this->_tpl_vars['LANG_USER_VIEW_ALL_LISTINGS']; ?>
</a></span>
							</div>
							<div class="clear_float"></div>
						</div>

						<?php if ($this->_tpl_vars['user']->content_fields->fieldsCount() && $this->_tpl_vars['user']->content_fields->isAnyValue()): ?>
							<div class="px10"></div>
							<h1><?php echo $this->_tpl_vars['LANG_LISTING_INFORMATION']; ?>
</h1>
		                   	<?php echo $this->_tpl_vars['user']->content_fields->outputMode(); ?>

						<?php endif; ?>

						<?php echo smarty_function_render_frontend_block(array('block_type' => 'listings','block_template' => 'frontend/blocks/listings_of_user.tpl','view_name' => 'semitable','view_format' => '3*1','search_owner' => $this->_tpl_vars['user']->login,'search_location' => $this->_tpl_vars['current_location'],'orderby' => 'l.creation_date','search_status' => 1,'search_users_status' => 2,'limit' => 3), $this);?>

                 	</div>
                </td>
                <td id="right_sidebar">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/right-sidebar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
			</tr>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>