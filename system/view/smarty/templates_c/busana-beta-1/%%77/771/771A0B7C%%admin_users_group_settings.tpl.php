<?php /* Smarty version 2.6.26, created on 2012-02-06 03:55:37
         compiled from users/admin_users_group_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'translate_content', 'users/admin_users_group_settings.tpl', 54, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->assign('users_group_id', $this->_tpl_vars['users_group']->id); ?>

<script language="JavaScript" type="text/javascript">
$(document).ready(function() {
	$("#is_own_page").click( function() {
		if ($('#is_own_page').is(':checked')) {
        	$('#use_seo_name').removeAttr('disabled');
        	$('#meta_enabled').removeAttr('disabled');
	    } else {
	        $('#use_seo_name').attr('disabled', true);
	        $('#meta_enabled').attr('disabled', true);
	    }
	});
	$("#logo_enabled").click( function() {
		if ($('#logo_enabled').is(':checked')) {
        	$('#logo_width').removeAttr('disabled');
        	$('#logo_height').removeAttr('disabled');
        	$('#logo_thumbnail_width').removeAttr('disabled');
        	$('#logo_thumbnail_height').removeAttr('disabled');
	    } else {
	        $('#logo_width').attr('disabled', true);
	        $('#logo_height').attr('disabled', true);
	        $('#logo_thumbnail_width').attr('disabled', true);
	        $('#logo_thumbnail_height').attr('disabled', true);
	    }
	});
});
</script>

				<div class="content">
                	<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

					<h3><?php if ($this->_tpl_vars['users_group_id'] != 'new'): ?><?php echo $this->_tpl_vars['LANG_EDIT_USERGROUP']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_CREATE_USERGROUP']; ?>
<?php endif; ?></h3>

					<?php if ($this->_tpl_vars['users_group_id'] != 'new'): ?>
					<div class="admin_top_menu_cell">
                     	<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/users_groups/create"); ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png" title="<?php echo $this->_tpl_vars['LANG_CREATE_NEW_GROUP']; ?>
" /></a>
						<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/users_groups/create"); ?>
"><?php echo $this->_tpl_vars['LANG_CREATE_NEW_GROUP']; ?>
</a>
					</div>

					<div class="admin_top_menu_cell">
						<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/users_groups/delete/".($this->_tpl_vars['users_group_id'])); ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_delete.png" title="<?php echo $this->_tpl_vars['LANG_DELETE_GROUP']; ?>
" /></a>
						<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/users_groups/delete/".($this->_tpl_vars['users_group_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_DELETE_GROUP']; ?>
</a>
					</div>
					<div class="clear_float"></div>
					<div class="px10"></div>
					<?php endif; ?>

					<form action="" method="post">
					<input type=hidden name="id" value="<?php echo $this->_tpl_vars['users_group_id']; ?>
">
					<div class="admin_option">
						<div class="admin_option_name" >
							<?php echo $this->_tpl_vars['LANG_USERS_GROUP_NAME']; ?>
<span class="red_asterisk">*</span>
							<?php echo smarty_function_translate_content(array('table' => 'users_groups','field' => 'name','row_id' => $this->_tpl_vars['users_group_id']), $this);?>

						</div>
						<input type=text name="name" value="<?php echo $this->_tpl_vars['users_group']->name; ?>
" size="40" class="admin_option_input">
					</div>
					<div class="admin_option">
						<div class="admin_option_name" >
							<?php echo $this->_tpl_vars['LANG_USERS_GROUP_OWN_PAGE']; ?>

						</div>
						<div class="admin_option_description" >
							<?php echo $this->_tpl_vars['LANG_USERS_GROUP_OWN_PAGE_DESCR']; ?>

						</div>
						<input type="checkbox" name="is_own_page" id="is_own_page" value="1" <?php if ($this->_tpl_vars['users_group']->is_own_page): ?>checked<?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

						
						<div class="px10"></div>
						
						<div class="admin_option_name" >
							<?php echo $this->_tpl_vars['LANG_USERS_GROUP_USE_SEO']; ?>

						</div>
						<div class="admin_option_description" >
							<?php echo $this->_tpl_vars['LANG_USERS_GROUP_USE_SEO_DESCR']; ?>

						</div>
						<input type="checkbox" name="use_seo_name" id="use_seo_name" value="1" <?php if ($this->_tpl_vars['users_group']->use_seo_name): ?>checked<?php endif; ?> <?php if (! $this->_tpl_vars['users_group']->is_own_page): ?>disabled<?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

						
						<div class="px10"></div>
						
						<div class="admin_option_name" >
							<?php echo $this->_tpl_vars['LANG_USERS_GROUP_META_ENABLED']; ?>

						</div>
						<div class="admin_option_description" >
							<?php echo $this->_tpl_vars['LANG_USERS_GROUP_META_ENABLED_DESCR']; ?>

						</div>
						<input type="checkbox" name="meta_enabled" id="meta_enabled" value="1" <?php if ($this->_tpl_vars['users_group']->meta_enabled): ?>checked<?php endif; ?> <?php if (! $this->_tpl_vars['users_group']->is_own_page): ?>disabled<?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_USERS_GROUP_LOGO_ENABLED']; ?>

						</div>
						<input type="checkbox" name="logo_enabled" id="logo_enabled" <?php if ($this->_tpl_vars['users_group']->logo_enabled == 1): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

						<div class="px10"></div>
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_USERS_GROUP_LOGO_SIZE']; ?>

						</div>
						<?php echo $this->_tpl_vars['LANG_WIDTH']; ?>
, <?php echo $this->_tpl_vars['LANG_PX']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['LANG_HEIGHT']; ?>
, <?php echo $this->_tpl_vars['LANG_PX']; ?>
<br>
						<input type=text name="logo_width" id="logo_width" value="<?php echo $this->_tpl_vars['users_group']->explodeSize('logo_size',0); ?>
" size="4" class="admin_option_input" <?php if ($this->_tpl_vars['users_group']->logo_enabled == 0): ?>disabled<?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=text name="logo_height" id="logo_height" value="<?php echo $this->_tpl_vars['users_group']->explodeSize('logo_size',1); ?>
" size="4" class="admin_option_input" <?php if ($this->_tpl_vars['users_group']->logo_enabled == 0): ?>disabled<?php endif; ?>>

						<div class="px10"></div>
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_USERS_GROUP_LOGO_THUMBNAIL_SIZE']; ?>

						</div>
						<?php echo $this->_tpl_vars['LANG_WIDTH']; ?>
, <?php echo $this->_tpl_vars['LANG_PX']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['LANG_HEIGHT']; ?>
, <?php echo $this->_tpl_vars['LANG_PX']; ?>
<br>
						<input type=text name="logo_thumbnail_width" id="logo_thumbnail_width" value="<?php echo $this->_tpl_vars['users_group']->explodeSize('logo_thumbnail_size',0); ?>
" size="4" class="admin_option_input" <?php if ($this->_tpl_vars['users_group']->logo_enabled == 0): ?>disabled<?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=text name="logo_thumbnail_height" id="logo_thumbnail_height" value="<?php echo $this->_tpl_vars['users_group']->explodeSize('logo_thumbnail_size',1); ?>
" size="4" class="admin_option_input" <?php if ($this->_tpl_vars['users_group']->logo_enabled == 0): ?>disabled<?php endif; ?>>
					</div>

					<input class="button save_button" type=submit name="submit" value="<?php if ($this->_tpl_vars['users_group_id'] != 'new'): ?><?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_BUTTON_CREATE_USERGROUP']; ?>
<?php endif; ?>">
					</form>
				</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>