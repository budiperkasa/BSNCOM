<?php /* Smarty version 2.6.26, created on 2012-03-05 07:05:56
         compiled from frontend/blocks/map_standart.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'addslashes', 'frontend/blocks/map_standart.tpl', 9, false),)), $this); ?>
<?php if ($this->_tpl_vars['items_array']): ?>
<?php if ($this->_tpl_vars['clasterization']): ?>
<script language="JavaScript" type="text/javascript" src="<?php echo $this->_tpl_vars['smarty_obj']->getFileInTheme('js/google_maps_clasterer.js'); ?>
"></script>
<?php endif; ?>
<script language="Javascript" type="text/javascript">
	map_markers_attrs_array.push(new map_markers_attrs(<?php echo $this->_tpl_vars['unique_map_id']; ?>
, eval(<?php echo $this->_tpl_vars['items_array']; ?>
)));
	var global_map_icons_path = '<?php echo $this->_tpl_vars['public_path']; ?>
map_icons/';
	var global_server_path = '<?php echo $this->_tpl_vars['users_content']; ?>
';
	var view_listing_label = '<?php echo smarty_function_addslashes(array('string' => $this->_tpl_vars['LANG_VIEW_LISTING']), $this);?>
';
	var view_summary_label = '<?php echo smarty_function_addslashes(array('string' => $this->_tpl_vars['LANG_LISTING_SUMMARY']), $this);?>
';

	$(document).ready(function() {
		$("#hide_map_link_<?php echo $this->_tpl_vars['unique_map_id']; ?>
").click(function() {
			$("#maps_canvas_<?php echo $this->_tpl_vars['unique_map_id']; ?>
").slideToggle(400);
		});
	});
</script>
<?php if ($this->_tpl_vars['radius_search_args']): ?>
	<?php if ($this->_tpl_vars['system_settings']['search_in_raduis_measure'] == 'miles'): ?>
		<input type=hidden id="map_in_miles_<?php echo $this->_tpl_vars['unique_map_id']; ?>
" value="true">
	<?php else: ?>
		<input type=hidden id="map_in_miles_<?php echo $this->_tpl_vars['unique_map_id']; ?>
" value="false">
	<?php endif; ?>
	<input type=hidden id="map_radius_<?php echo $this->_tpl_vars['unique_map_id']; ?>
" value="<?php echo $this->_tpl_vars['radius_search_args']['radius']; ?>
">
	<input type=hidden id="map_coord_1_<?php echo $this->_tpl_vars['unique_map_id']; ?>
" value="<?php echo $this->_tpl_vars['radius_search_args']['map_coord_1']; ?>
">
	<input type=hidden id="map_coord_2_<?php echo $this->_tpl_vars['unique_map_id']; ?>
" value="<?php echo $this->_tpl_vars['radius_search_args']['map_coord_2']; ?>
">
<?php endif; ?>
<a href="javascript: void(0)" id="hide_map_link_<?php echo $this->_tpl_vars['unique_map_id']; ?>
">Hide/show map</a>
<div class="px5"></div>
<div id="maps_canvas_<?php echo $this->_tpl_vars['unique_map_id']; ?>
" class="maps_canvas" <?php if ($this->_tpl_vars['map_width'] || $this->_tpl_vars['map_height']): ?>style="<?php if ($this->_tpl_vars['map_width']): ?>width: <?php echo $this->_tpl_vars['map_width']; ?>
px;<?php endif; ?> <?php if ($this->_tpl_vars['map_height']): ?>height: <?php echo $this->_tpl_vars['map_height']; ?>
px;<?php endif; ?>"<?php endif; ?>></div>
<?php endif; ?>