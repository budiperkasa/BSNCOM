<?php /* Smarty version 2.6.26, created on 2012-02-06 04:25:24
         compiled from locations/drop_boxes.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'locations/drop_boxes.tpl', 6, false),)), $this); ?>
<script language="JavaScript" type="text/javascript">
	$(document).ready(function() {
		$(".location_dropdown_list").bind("change", function() {
			curr_order_num = $(this).attr('order_num');
			next_order_num = parseFloat(curr_order_num) + 1;
			if (curr_order_num != <?php echo count($this->_tpl_vars['location_levels']); ?>
 && this.options[this.selectedIndex].value != '') {
				$(this).parent().parent().find(".location_dropdown_list").attr('disabled', 'disabled');
				$(this).parent().parent().find(".location_dropdown_list").css('background', 'url(<?php echo $this->_tpl_vars['public_path']; ?>
images/ajax-indicator.gif) no-repeat 50% 50%');
				for (i = next_order_num; i <= <?php echo count($this->_tpl_vars['location_levels']); ?>
; i++)
					$(this).parent().parent().find(".loc_level_"+i+"_<?php echo $this->_tpl_vars['virtual_id']; ?>
").html('<option value="" selected>- - - <?php echo $this->_tpl_vars['LANG_LOCATION_SELECT']; ?>
 ' + $(this).parent().parent().find(".loc_level_"+i+"_<?php echo $this->_tpl_vars['virtual_id']; ?>
").attr("level_name") + ' - - -</option>');
				$(this).parent().parent().find(".loc_level_"+next_order_num+"_<?php echo $this->_tpl_vars['virtual_id']; ?>
").load("<?php echo $this->_tpl_vars['VH']->site_url('ajax/locations/build_drop_box'); ?>
", {parent_id: this.options[this.selectedIndex].value, for_level: $(this).parent().parent().find(".loc_level_"+next_order_num+"_<?php echo $this->_tpl_vars['virtual_id']; ?>
").attr("level_name")},
				function(){
					$(this).parent().parent().find(".location_dropdown_list").css('background','');
					$(this).parent().parent().find(".location_dropdown_list").attr('disabled', '');
				});
			}
		});
	});
</script>

<div>
<?php $_from = $this->_tpl_vars['location_levels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['loc_level']):
?>
<?php $this->assign('order_num', $this->_tpl_vars['loc_level']->order_num); ?>
	<div class="location_level">
		<select class="location_dropdown_list loc_level_<?php echo $this->_tpl_vars['loc_level']->order_num; ?>
_<?php echo $this->_tpl_vars['virtual_id']; ?>
" order_num="<?php echo $this->_tpl_vars['loc_level']->order_num; ?>
" level_name="<?php echo $this->_tpl_vars['loc_level']->name; ?>
" name="loc_level_<?php echo $this->_tpl_vars['loc_level']->order_num; ?>
[]" style="width: 300px;">
			<option value="">- - - <?php echo $this->_tpl_vars['LANG_LOCATION_SELECT']; ?>
 <?php echo $this->_tpl_vars['loc_level']->name; ?>
 - - -</option>
			<?php $_from = $this->_tpl_vars['locations_by_level'][$this->_tpl_vars['order_num']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['location']):
?>
				<option value="<?php echo $this->_tpl_vars['location']->id; ?>
" <?php if ($this->_tpl_vars['location']->selected): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['location']->name; ?>
</option>
			<?php endforeach; endif; unset($_from); ?>
		</select>
	</div>
<?php endforeach; endif; unset($_from); ?>
</div>