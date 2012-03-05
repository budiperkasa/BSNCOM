<?php /* Smarty version 2.6.26, created on 2012-02-06 08:03:48
         compiled from content_fields/search/select/search_input.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'content_fields/search/select/search_input.tpl', 41, false),)), $this); ?>
<script language="JavaScript" type="text/javascript">
jQuery( function($) {
	$("#any_<?php echo $this->_tpl_vars['field']->seo_name; ?>
").change( function() {
		if ($("#any_<?php echo $this->_tpl_vars['field']->seo_name; ?>
").is(":checked")) {
			$(".<?php echo $this->_tpl_vars['field_index']; ?>
").each( function() {
				$(this).attr('disabled', 'disabled');
			});
		} else {
			$(".<?php echo $this->_tpl_vars['field_index']; ?>
").each( function() {
				$(this).attr('disabled', '');
			});
		}
	});

	$("#search_form").submit( function() {
		if (!$("#any_<?php echo $this->_tpl_vars['field']->seo_name; ?>
").is(":checked")) {
			var options = [];
			$(".<?php echo $this->_tpl_vars['field_index']; ?>
:checked").each( function() {
				options.push($(this).val());
			});
			var url = options.join('-');
		} else
			url = 'any';

		if (url != '') {
			global_js_url = global_js_url + "<?php echo $this->_tpl_vars['field_index']; ?>
" + '/' + url + '/';
			if (!$("#any_<?php echo $this->_tpl_vars['field']->seo_name; ?>
").is(":checked"))
				global_js_url = global_js_url + "<?php echo $this->_tpl_vars['field_mode']; ?>
" + '/' + $("input[name=<?php echo $this->_tpl_vars['field_mode']; ?>
]:checked").val() + '/';
		}
		
		window.location.href = global_js_url;
		return false;
	});
});
</script>

							<div class="search_item">
								<label><?php if ($this->_tpl_vars['field']->frontend_name): ?><?php echo $this->_tpl_vars['field']->frontend_name; ?>
<?php else: ?><?php echo $this->_tpl_vars['field']->name; ?>
<?php endif; ?></label>
								<div>
									<table cellspacing="3" cellpadding="0">
										<?php if (count($this->_tpl_vars['options'])): ?>
										<tr>
											<td width="10px" colspan=3><input type="checkbox" id="any_<?php echo $this->_tpl_vars['field']->seo_name; ?>
" <?php if ($this->_tpl_vars['check_all']): ?>checked<?php endif; ?> />
												<?php echo $this->_tpl_vars['LANG_SHOW_ALL']; ?>

											</td>
										</tr>
										<?php endif; ?>
										<tr>
											<?php $this->assign('td_padding_pixels', 20); ?>
											<?php $this->assign('i', 0); ?>

											<td style="padding-right: <?php echo $this->_tpl_vars['td_padding_pixels']; ?>
px" valign="top">
												<?php unset($this->_sections['key']);
$this->_sections['key']['name'] = 'key';
$this->_sections['key']['loop'] = is_array($_loop=$this->_tpl_vars['options']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['key']['start'] = (int)0;
$this->_sections['key']['step'] = ((int)3) == 0 ? 1 : (int)3;
$this->_sections['key']['show'] = true;
$this->_sections['key']['max'] = $this->_sections['key']['loop'];
if ($this->_sections['key']['start'] < 0)
    $this->_sections['key']['start'] = max($this->_sections['key']['step'] > 0 ? 0 : -1, $this->_sections['key']['loop'] + $this->_sections['key']['start']);
else
    $this->_sections['key']['start'] = min($this->_sections['key']['start'], $this->_sections['key']['step'] > 0 ? $this->_sections['key']['loop'] : $this->_sections['key']['loop']-1);
if ($this->_sections['key']['show']) {
    $this->_sections['key']['total'] = min(ceil(($this->_sections['key']['step'] > 0 ? $this->_sections['key']['loop'] - $this->_sections['key']['start'] : $this->_sections['key']['start']+1)/abs($this->_sections['key']['step'])), $this->_sections['key']['max']);
    if ($this->_sections['key']['total'] == 0)
        $this->_sections['key']['show'] = false;
} else
    $this->_sections['key']['total'] = 0;
if ($this->_sections['key']['show']):

            for ($this->_sections['key']['index'] = $this->_sections['key']['start'], $this->_sections['key']['iteration'] = 1;
                 $this->_sections['key']['iteration'] <= $this->_sections['key']['total'];
                 $this->_sections['key']['index'] += $this->_sections['key']['step'], $this->_sections['key']['iteration']++):
$this->_sections['key']['rownum'] = $this->_sections['key']['iteration'];
$this->_sections['key']['index_prev'] = $this->_sections['key']['index'] - $this->_sections['key']['step'];
$this->_sections['key']['index_next'] = $this->_sections['key']['index'] + $this->_sections['key']['step'];
$this->_sections['key']['first']      = ($this->_sections['key']['iteration'] == 1);
$this->_sections['key']['last']       = ($this->_sections['key']['iteration'] == $this->_sections['key']['total']);
?>
													<label style="display:block;">
													<nobr>
														<input type="checkbox" name="<?php echo $this->_tpl_vars['field_index']; ?>
_<?php echo $this->_tpl_vars['key']; ?>
" value="<?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['id']; ?>
" class="search_<?php echo $this->_tpl_vars['field']->seo_name; ?>
" <?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['checked']; ?>
 <?php if ($this->_tpl_vars['check_all']): ?>disabled<?php endif; ?> />
														<?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['option_name']; ?>

													</nobr>
													</label>
												<?php endfor; endif; ?>
											</td>
											
											<td style="padding-right: <?php echo $this->_tpl_vars['td_padding_pixels']; ?>
px" valign="top">
												<?php unset($this->_sections['key']);
$this->_sections['key']['name'] = 'key';
$this->_sections['key']['loop'] = is_array($_loop=$this->_tpl_vars['options']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['key']['start'] = (int)1;
$this->_sections['key']['step'] = ((int)3) == 0 ? 1 : (int)3;
$this->_sections['key']['show'] = true;
$this->_sections['key']['max'] = $this->_sections['key']['loop'];
if ($this->_sections['key']['start'] < 0)
    $this->_sections['key']['start'] = max($this->_sections['key']['step'] > 0 ? 0 : -1, $this->_sections['key']['loop'] + $this->_sections['key']['start']);
else
    $this->_sections['key']['start'] = min($this->_sections['key']['start'], $this->_sections['key']['step'] > 0 ? $this->_sections['key']['loop'] : $this->_sections['key']['loop']-1);
if ($this->_sections['key']['show']) {
    $this->_sections['key']['total'] = min(ceil(($this->_sections['key']['step'] > 0 ? $this->_sections['key']['loop'] - $this->_sections['key']['start'] : $this->_sections['key']['start']+1)/abs($this->_sections['key']['step'])), $this->_sections['key']['max']);
    if ($this->_sections['key']['total'] == 0)
        $this->_sections['key']['show'] = false;
} else
    $this->_sections['key']['total'] = 0;
if ($this->_sections['key']['show']):

            for ($this->_sections['key']['index'] = $this->_sections['key']['start'], $this->_sections['key']['iteration'] = 1;
                 $this->_sections['key']['iteration'] <= $this->_sections['key']['total'];
                 $this->_sections['key']['index'] += $this->_sections['key']['step'], $this->_sections['key']['iteration']++):
$this->_sections['key']['rownum'] = $this->_sections['key']['iteration'];
$this->_sections['key']['index_prev'] = $this->_sections['key']['index'] - $this->_sections['key']['step'];
$this->_sections['key']['index_next'] = $this->_sections['key']['index'] + $this->_sections['key']['step'];
$this->_sections['key']['first']      = ($this->_sections['key']['iteration'] == 1);
$this->_sections['key']['last']       = ($this->_sections['key']['iteration'] == $this->_sections['key']['total']);
?>
													<label style="display:block;">
													<nobr>
														<input type="checkbox" name="<?php echo $this->_tpl_vars['field_index']; ?>
_<?php echo $this->_tpl_vars['key']; ?>
" value="<?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['id']; ?>
" class="search_<?php echo $this->_tpl_vars['field']->seo_name; ?>
" <?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['checked']; ?>
 <?php if ($this->_tpl_vars['check_all']): ?>disabled<?php endif; ?> />
														<?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['option_name']; ?>

													</nobr>
													</label>
												<?php endfor; endif; ?>
											</td>
											
											<td style="padding-right: <?php echo $this->_tpl_vars['td_padding_pixels']; ?>
px" valign="top">
												<?php unset($this->_sections['key']);
$this->_sections['key']['name'] = 'key';
$this->_sections['key']['loop'] = is_array($_loop=$this->_tpl_vars['options']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['key']['start'] = (int)2;
$this->_sections['key']['step'] = ((int)3) == 0 ? 1 : (int)3;
$this->_sections['key']['show'] = true;
$this->_sections['key']['max'] = $this->_sections['key']['loop'];
if ($this->_sections['key']['start'] < 0)
    $this->_sections['key']['start'] = max($this->_sections['key']['step'] > 0 ? 0 : -1, $this->_sections['key']['loop'] + $this->_sections['key']['start']);
else
    $this->_sections['key']['start'] = min($this->_sections['key']['start'], $this->_sections['key']['step'] > 0 ? $this->_sections['key']['loop'] : $this->_sections['key']['loop']-1);
if ($this->_sections['key']['show']) {
    $this->_sections['key']['total'] = min(ceil(($this->_sections['key']['step'] > 0 ? $this->_sections['key']['loop'] - $this->_sections['key']['start'] : $this->_sections['key']['start']+1)/abs($this->_sections['key']['step'])), $this->_sections['key']['max']);
    if ($this->_sections['key']['total'] == 0)
        $this->_sections['key']['show'] = false;
} else
    $this->_sections['key']['total'] = 0;
if ($this->_sections['key']['show']):

            for ($this->_sections['key']['index'] = $this->_sections['key']['start'], $this->_sections['key']['iteration'] = 1;
                 $this->_sections['key']['iteration'] <= $this->_sections['key']['total'];
                 $this->_sections['key']['index'] += $this->_sections['key']['step'], $this->_sections['key']['iteration']++):
$this->_sections['key']['rownum'] = $this->_sections['key']['iteration'];
$this->_sections['key']['index_prev'] = $this->_sections['key']['index'] - $this->_sections['key']['step'];
$this->_sections['key']['index_next'] = $this->_sections['key']['index'] + $this->_sections['key']['step'];
$this->_sections['key']['first']      = ($this->_sections['key']['iteration'] == 1);
$this->_sections['key']['last']       = ($this->_sections['key']['iteration'] == $this->_sections['key']['total']);
?>
													<label style="display:block;">
													<nobr>
														<input type="checkbox" name="<?php echo $this->_tpl_vars['field_index']; ?>
_<?php echo $this->_tpl_vars['key']; ?>
" value="<?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['id']; ?>
" class="search_<?php echo $this->_tpl_vars['field']->seo_name; ?>
" <?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['checked']; ?>
 <?php if ($this->_tpl_vars['check_all']): ?>disabled<?php endif; ?> />
														<?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['option_name']; ?>

													</nobr>
													</label>
												<?php endfor; endif; ?>
											</td>
										</tr>
									</table>
								</div>
                     		</div>