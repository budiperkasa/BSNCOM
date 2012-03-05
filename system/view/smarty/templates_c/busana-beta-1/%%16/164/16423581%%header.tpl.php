<?php /* Smarty version 2.6.26, created on 2012-03-05 07:19:14
         compiled from frontend/header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'addslashes', 'frontend/header.tpl', 38, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title><?php if ($this->_tpl_vars['title']): ?><?php echo $this->_tpl_vars['title']; ?>
 - <?php endif; ?><?php echo $this->_tpl_vars['site_settings']['website_title']; ?>
</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="description" content="<?php if ($this->_tpl_vars['description']): ?><?php echo $this->_tpl_vars['description']; ?>
<?php else: ?><?php echo $this->_tpl_vars['site_settings']['description']; ?>
<?php endif; ?>" />
		<meta name="keywords" content="<?php if ($this->_tpl_vars['keywords']): ?><?php echo $this->_tpl_vars['keywords']; ?>
<?php else: ?><?php echo $this->_tpl_vars['site_settings']['keywords']; ?>
<?php endif; ?>" />
<?php $_from = $this->_tpl_vars['css_files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['css_file'] => $this->_tpl_vars['css_media']):
?>
		<link rel="stylesheet" href="<?php echo $this->_tpl_vars['css_file']; ?>
" media="<?php echo $this->_tpl_vars['css_media']; ?>
" type="text/css" />
<?php endforeach; endif; unset($_from); ?>
<?php $_from = $this->_tpl_vars['ex_css_files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ex_css_item']):
?>
		<link rel="stylesheet" href="<?php echo $this->_tpl_vars['ex_css_item']; ?>
" type="text/css" />
<?php endforeach; endif; unset($_from); ?>
<?php $_from = $this->_tpl_vars['ex_js_scripts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ex_js_item']):
?>
		<script language="JavaScript" type="text/javascript" src="<?php echo $this->_tpl_vars['ex_js_item']; ?>
"></script>
<?php endforeach; endif; unset($_from); ?>

<?php if (! $this->_tpl_vars['CI']->config->item('combine_static_files') || $this->_tpl_vars['CI']->config->item('combine_static_files') === null): ?>
	<?php $_from = $this->_tpl_vars['js_scripts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['js_item']):
?>
			<script language="JavaScript" type="text/javascript" src="<?php echo $this->_tpl_vars['VH']->base_url(); ?>
<?php echo $this->_tpl_vars['js_item']; ?>
"></script>
	<?php endforeach; endif; unset($_from); ?>
<?php else: ?>
	<script language="JavaScript" type="text/javascript" src="<?php echo $this->_tpl_vars['VH']->base_url(); ?>
,<?php echo $this->_tpl_vars['VH']->implode(',',$this->_tpl_vars['js_scripts']); ?>
"></script>
<?php endif; ?>
		<?php if ($this->_tpl_vars['CI']->load->is_module_loaded('rss')): ?>
			<?php if ($this->_tpl_vars['VH']->getRssTitle()): ?>
				<link title="<?php echo $this->_tpl_vars['VH']->getRssTitle(); ?>
" type="application/rss+xml" rel="alternate" href="<?php echo $this->_tpl_vars['VH']->getRssUrl(); ?>
" />
			<?php endif; ?>
		<?php endif; ?>
		<link rel="shortcut icon" href="<?php echo $this->_tpl_vars['public_path']; ?>
images/favicon.ico" >
	</head>
<body>
<div id="messages"></div>

<script language="Javascript" type="text/javascript">
	var in_favourites_icon = $('<img />').attr('src', '<?php echo $this->_tpl_vars['public_path']; ?>
/images/icons/folder_star.png');
	var not_in_favourites_icon = $('<img />').attr('src', '<?php echo $this->_tpl_vars['public_path']; ?>
/images/icons/folder_star_grscl.png');
	var to_favourites_msg = '<?php echo smarty_function_addslashes(array('string' => $this->_tpl_vars['LANG_QUICK_LIST_SUCCESS']), $this);?>
';
	var from_favourites_msg = '<?php echo smarty_function_addslashes(array('string' => $this->_tpl_vars['LANG_QUICK_FROM_LIST_SUCCESS']), $this);?>
';
</script>

<div id="ajax_loader"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/ajax-loader.gif"></div>
<!-- Wrapper Starts -->
	<div id="wrapper">

	<!-- Header Starts -->
		<div id="header_content">
		<!-- Logo Starts -->
			<div id="header_left">
				<?php if ($this->_tpl_vars['system_settings']['site_logo_file']): ?>
				<a href="<?php echo $this->_tpl_vars['VH']->index_url(); ?>
">
					<img src="<?php echo $this->_tpl_vars['users_content']; ?>
/users_images/site_logo/<?php echo $this->_tpl_vars['system_settings']['site_logo_file']; ?>
" />
				</a>
				<?php endif; ?>
			</div>
		<!-- Logo Ends -->
		<!-- I18n panels Starts -->
			<div id="header_right">

			</div>
		<!-- I18n panels Ends -->
		</div>
		<!-- Header Ends -->

		<!-- Menu Starts  -->
		<?php if (! $this->_tpl_vars['system_settings']['single_type_structure']): ?>
		<div id="menu">
			<ul>
				<?php $this->assign('i', 0); ?>
				<?php $_from = $this->_tpl_vars['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type']):
?>
					<li><a href="<?php echo $this->_tpl_vars['VH']->site_url($this->_tpl_vars['type']->getUrl()); ?>
" class="<?php if ($this->_tpl_vars['type']->id == $this->_tpl_vars['current_type']->id): ?>active_type<?php else: ?>clear_type<?php endif; ?>"><?php echo $this->_tpl_vars['type']->name; ?>
</a></li>
				<?php endforeach; endif; unset($_from); ?>
			</ul>
		</div>
		<?php endif; ?>
		<!-- Menu Ends -->

		<!-- Content Starts -->
		<div id="main">
			<table width="100%" valign="top" cellpadding="0" cellspacing="0">