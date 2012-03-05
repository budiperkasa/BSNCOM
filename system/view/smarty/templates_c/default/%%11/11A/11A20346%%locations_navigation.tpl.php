<?php /* Smarty version 2.6.26, created on 2012-02-06 02:23:50
         compiled from frontend/blocks/locations_navigation.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'addslashes', 'frontend/blocks/locations_navigation.tpl', 8, false),)), $this); ?>
<?php if ($this->_tpl_vars['locations_tree']): ?>
<?php $this->assign('base_url', $this->_tpl_vars['VH']->getBaseUrl()); ?>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
	$("#other_locations").click(function() {
		$("#hidden_locations").slideToggle(300, function() {
			if ($(this).is(":visible"))
				$("#other_locations").html('<<&nbsp;<?php echo smarty_function_addslashes(array('string' => $this->_tpl_vars['LANG_LOCATIONS_HIDE']), $this);?>
');
			else
				$("#other_locations").html('<?php echo smarty_function_addslashes(array('string' => $this->_tpl_vars['LANG_LOCATIONS_OTHER']), $this);?>
&nbsp;>>');
		});
		return false;
	})
});
</script>
<div id="navigation_block">
	<div class="active_location">
		<span class="search_in_span">
			<?php echo $this->_tpl_vars['LANG_LOCATIONS_SEARCH_IN_1']; ?>

			<?php if ($this->_tpl_vars['current_type']): ?><span class="search_in_span_object"><?php echo $this->_tpl_vars['current_type']->name; ?>
</span><?php endif; ?>
			<?php if ($this->_tpl_vars['current_category']): ?><span class="search_in_span_object"><?php if ($this->_tpl_vars['current_type']): ?>/<?php endif; ?><?php echo $this->_tpl_vars['current_category']->getChainAsString(); ?>
</span><?php endif; ?>
			<?php echo $this->_tpl_vars['LANG_LOCATIONS_SEARCH_IN_2']; ?>
:
		</span>
		&nbsp;&nbsp;<?php if ($this->_tpl_vars['current_location']): ?><b><?php echo $this->_tpl_vars['current_location']->getChainAsString(' » ',false); ?>
</b>&nbsp;&nbsp;|&nbsp;&nbsp;<?php endif; ?>&nbsp;<?php if (! $this->_tpl_vars['current_location']): ?><b><?php echo $this->_tpl_vars['LANG_LOCATIONS_EVERYWHERE']; ?>
</b><?php else: ?><a href="<?php echo $this->_tpl_vars['VH']->site_url("location/any/".($this->_tpl_vars['base_url'])); ?>
" rel="nofollow"><?php echo $this->_tpl_vars['LANG_LOCATIONS_EVERYWHERE']; ?>
</a><?php endif; ?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript: void(0);" id="other_locations"><?php echo $this->_tpl_vars['LANG_LOCATIONS_OTHER']; ?>
 >></a>
	</div>
	<div class="clear_float"></div>
	<div id="hidden_locations" style="display: none;">
		<?php ob_start();
$_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'frontend/locations/navigate.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
$this->assign('template', ob_get_contents()); ob_end_clean();
 ?>
		<?php $_from = $this->_tpl_vars['locations_tree']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['location']):
?>
			<div style="padding-bottom: 25px">
				<?php echo $this->_tpl_vars['location']->render($this->_tpl_vars['template'],$this->_tpl_vars['is_counter'],$this->_tpl_vars['max_depth'],$this->_tpl_vars['current_location']->seo_name,'class="labeled_location_selected"','class="labeled_location_root"',$this->_tpl_vars['is_only_labeled']); ?>

			</div>
			<div class="clear_float"></div>
		<?php endforeach; endif; unset($_from); ?>
	</div>
</div>
<div class="px5"></div>
<?php endif; ?>