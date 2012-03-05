<?php /* Smarty version 2.6.26, created on 2012-02-06 04:44:46
         compiled from content_fields/fields/checkboxes/field_input.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'content_fields/fields/checkboxes/field_input.tpl', 5, false),)), $this); ?>
			<div class="admin_option_name">
				<?php echo $this->_tpl_vars['field']->name; ?>
<?php if ($this->_tpl_vars['field']->required): ?><span class="red_asterisk">*</span><?php endif; ?>
			</div>
			<div class="admin_option_description">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['field']->description)) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

			</div>
			<table cellspacing="3" cellpadding="0">
				<tr>
					<?php $this->assign('td_padding_pixels', 50); ?>
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
							<nobr>
							<label>
								<input type="checkbox" name="field_<?php echo $this->_tpl_vars['field']->seo_name; ?>
_<?php echo $this->_tpl_vars['i']++; ?>
" value="<?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['id']; ?>
" <?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['checked']; ?>
>
								<?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['option_name']; ?>

							</label>
							</nobr>
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
							<nobr>
							<label>
								<input type="checkbox" name="field_<?php echo $this->_tpl_vars['field']->seo_name; ?>
_<?php echo $this->_tpl_vars['i']++; ?>
" value="<?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['id']; ?>
" <?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['checked']; ?>
>
								<?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['option_name']; ?>

							</label>
							</nobr>
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
							<nobr>
							<label>
								<input type="checkbox" name="field_<?php echo $this->_tpl_vars['field']->seo_name; ?>
_<?php echo $this->_tpl_vars['i']++; ?>
" value="<?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['id']; ?>
" <?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['checked']; ?>
>
								<?php echo $this->_tpl_vars['options'][$this->_sections['key']['index']]['option_name']; ?>

							</label>
							</nobr>
						<?php endfor; endif; ?>
					</td>
				</tr>
			</table>
			<br />
			<br />