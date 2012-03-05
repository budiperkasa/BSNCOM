<?php /* Smarty version 2.6.26, created on 2012-02-06 08:44:31
         compiled from frontend/wrappers/wrapper_listings_semitable.tpl */ ?>
<?php $this->assign('i', 0); ?>
	<?php $_from = $this->_tpl_vars['listings_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['listing']):
?>
		<?php $this->assign('i', $this->_tpl_vars['i']+1); ?>
		<?php if ($this->_tpl_vars['i'] == 1): ?>
			<div style="padding-bottom: 10px">
			<table width="100%" cellspacing="0"><tr>
			<td width="<?php echo $this->_tpl_vars['td_width']; ?>
px" class="listing_preview <?php if ($this->_tpl_vars['listing']->level->featured): ?>featured<?php endif; ?>"><?php echo $this->_tpl_vars['listing']->view('semitable'); ?>
</td>
		<?php else: ?>
			<td width="<?php echo $this->_tpl_vars['td_space_width']; ?>
px"></td>
			<td width="<?php echo $this->_tpl_vars['td_width']; ?>
px" class="listing_preview <?php if ($this->_tpl_vars['listing']->level->featured): ?>featured<?php endif; ?>"><?php echo $this->_tpl_vars['listing']->view('semitable'); ?>
</td>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['i'] == $this->_tpl_vars['columns']): ?>
			</tr></table>
			</div>
			<?php $this->assign('i', 0); ?>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	<?php if ($this->_tpl_vars['i'] > 0): ?>
		<?php unset($this->_sections['myLoop']);
$this->_sections['myLoop']['name'] = 'myLoop';
$this->_sections['myLoop']['start'] = (int)$this->_tpl_vars['i'];
$this->_sections['myLoop']['loop'] = is_array($_loop=$this->_tpl_vars['columns']-$this->_tpl_vars['i']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['myLoop']['show'] = true;
$this->_sections['myLoop']['max'] = $this->_sections['myLoop']['loop'];
$this->_sections['myLoop']['step'] = 1;
if ($this->_sections['myLoop']['start'] < 0)
    $this->_sections['myLoop']['start'] = max($this->_sections['myLoop']['step'] > 0 ? 0 : -1, $this->_sections['myLoop']['loop'] + $this->_sections['myLoop']['start']);
else
    $this->_sections['myLoop']['start'] = min($this->_sections['myLoop']['start'], $this->_sections['myLoop']['step'] > 0 ? $this->_sections['myLoop']['loop'] : $this->_sections['myLoop']['loop']-1);
if ($this->_sections['myLoop']['show']) {
    $this->_sections['myLoop']['total'] = min(ceil(($this->_sections['myLoop']['step'] > 0 ? $this->_sections['myLoop']['loop'] - $this->_sections['myLoop']['start'] : $this->_sections['myLoop']['start']+1)/abs($this->_sections['myLoop']['step'])), $this->_sections['myLoop']['max']);
    if ($this->_sections['myLoop']['total'] == 0)
        $this->_sections['myLoop']['show'] = false;
} else
    $this->_sections['myLoop']['total'] = 0;
if ($this->_sections['myLoop']['show']):

            for ($this->_sections['myLoop']['index'] = $this->_sections['myLoop']['start'], $this->_sections['myLoop']['iteration'] = 1;
                 $this->_sections['myLoop']['iteration'] <= $this->_sections['myLoop']['total'];
                 $this->_sections['myLoop']['index'] += $this->_sections['myLoop']['step'], $this->_sections['myLoop']['iteration']++):
$this->_sections['myLoop']['rownum'] = $this->_sections['myLoop']['iteration'];
$this->_sections['myLoop']['index_prev'] = $this->_sections['myLoop']['index'] - $this->_sections['myLoop']['step'];
$this->_sections['myLoop']['index_next'] = $this->_sections['myLoop']['index'] + $this->_sections['myLoop']['step'];
$this->_sections['myLoop']['first']      = ($this->_sections['myLoop']['iteration'] == 1);
$this->_sections['myLoop']['last']       = ($this->_sections['myLoop']['iteration'] == $this->_sections['myLoop']['total']);
?>
			<td width="<?php echo $this->_tpl_vars['td_space_width']; ?>
px"></td>
			<td width="<?php echo $this->_tpl_vars['td_width']+$this->_tpl_vars['td_padding_border']; ?>
px"></td>
		<?php endfor; endif; ?>
		</tr></table></div>
	<?php endif; ?>