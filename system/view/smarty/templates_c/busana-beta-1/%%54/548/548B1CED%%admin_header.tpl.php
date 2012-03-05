<?php /* Smarty version 2.6.26, created on 2012-02-06 03:14:04
         compiled from backend/admin_header.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title><?php echo $this->_tpl_vars['title']; ?>
<?php if ($this->_tpl_vars['site_settings']['website_title']): ?> - <?php echo $this->_tpl_vars['site_settings']['website_title']; ?>
<?php endif; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
		<link rel="shortcut icon" href="<?php echo $this->_tpl_vars['public_path']; ?>
images/favicon.ico" >
	</head>
<body>
<div id="messages"></div>
<div id="ajax_loader"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/ajax-loader.gif"></div>
<div id="wrapper">
      <div id="header_content">
           <table width="100%">
           	<tr>
           		<td id="header_left">
           			<?php if ($this->_tpl_vars['system_settings']['site_logo_file']): ?>
           				<a href="<?php echo $this->_tpl_vars['VH']->site_url(); ?>
">
           					<img src="<?php echo $this->_tpl_vars['users_content']; ?>
/users_images/site_logo/<?php echo $this->_tpl_vars['system_settings']['site_logo_file']; ?>
">
           				</a>
           			<?php endif; ?>
           		</td>
           		<td id="header_right">
           			<!-- If i18n module enabled - insert languages panel -->
           			<?php if ($this->_tpl_vars['CI']->load->is_module_loaded('i18n')): ?>
           				<?php echo $this->_tpl_vars['VH']->buildLanguagesPanels($this->_tpl_vars['CI']); ?>

           			<?php endif; ?>
           		</td>
           	</tr>
           </table>
      </div>

      <div id="main">
      	<table width="100%">
      		<tr id="content_table">
      			<?php if ($this->_tpl_vars['CI']->router->fetch_module() != 'authorization' && $this->_tpl_vars['CI']->router->fetch_module() != 'install'): ?>
      				<?php echo $this->_tpl_vars['VH']->buildAdminMenu($this->_tpl_vars['CI']); ?>

      			<?php endif; ?>
      			<td style="padding: 0 0 0 10px;">
      				<div id="content_wrapper">
      				<?php echo $this->_tpl_vars['VH']->buildBreadcrumbs($this->_tpl_vars['CI']); ?>