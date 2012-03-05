<?php /* Smarty version 2.6.26, created on 2012-03-05 07:12:38
         compiled from frontend/wrappers/wrapper_listings_short.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 'frontend/wrappers/wrapper_listings_short.tpl', 41, false),)), $this); ?>
<div style="padding-bottom: 10px;">
	<table cellspacing="0" cellpadding="0" width="100%">
		<tr class="content_field_header">
			<th></th>
			<?php if ($this->_tpl_vars['order_url'] !== null): ?>
				<?php if ($this->_tpl_vars['orderby'] == 'l.creation_date' && $this->_tpl_vars['direction'] == 'asc'): ?>
					<th class="content_field_header_cell"><a class="descending" title="<?php echo $this->_tpl_vars['LANG_SORT_DESCENDING']; ?>
" href="<?php echo $this->_tpl_vars['order_url']; ?>
orderby/l.creation_date/direction/desc/" rel="nofollow"><?php echo $this->_tpl_vars['LANG_DATE']; ?>
</a></th>
				<?php elseif ($this->_tpl_vars['orderby'] == 'l.creation_date' && $this->_tpl_vars['direction'] == 'desc'): ?>
					<th class="content_field_header_cell"><a class="ascending" title="<?php echo $this->_tpl_vars['LANG_SORT_ASCENDING']; ?>
" href="<?php echo $this->_tpl_vars['order_url']; ?>
orderby/l.creation_date/direction/asc/" rel="nofollow"><?php echo $this->_tpl_vars['LANG_DATE']; ?>
</a></th>
				<?php else: ?>
					<th class="content_field_header_cell"><a title="<?php echo $this->_tpl_vars['LANG_SORT_DESCENDING']; ?>
" href="<?php echo $this->_tpl_vars['order_url']; ?>
orderby/l.creation_date/direction/desc/" rel="nofollow"><?php echo $this->_tpl_vars['LANG_DATE']; ?>
</a></th>
				<?php endif; ?>
			<?php else: ?>
				<th class="content_field_header_cell"><?php echo $this->_tpl_vars['LANG_DATE']; ?>
</th>
			<?php endif; ?>
			
			<?php if ($this->_tpl_vars['logo_enabled']): ?>
				<th class="content_field_header_cell" style="padding:0;"><?php echo $this->_tpl_vars['LANG_LOGO']; ?>
</th>
			<?php else: ?>
				<th class="content_field_header_cell" style="padding:0;"></th>
			<?php endif; ?>
			
			<?php if ($this->_tpl_vars['order_url'] !== null): ?>
				<?php if ($this->_tpl_vars['orderby'] == 'l.title' && $this->_tpl_vars['direction'] == 'asc'): ?>
					<th class="content_field_header_cell"><a class="descending" title="<?php echo $this->_tpl_vars['LANG_SORT_DESCENDING']; ?>
" href="<?php echo $this->_tpl_vars['order_url']; ?>
orderby/l.title/direction/desc/" rel="nofollow"><?php echo $this->_tpl_vars['LANG_TITLE']; ?>
</a></th>
				<?php elseif ($this->_tpl_vars['orderby'] == 'l.title' && $this->_tpl_vars['direction'] == 'desc'): ?>
					<th class="content_field_header_cell"><a class="ascending" title="<?php echo $this->_tpl_vars['LANG_SORT_ASCENDING']; ?>
" href="<?php echo $this->_tpl_vars['order_url']; ?>
orderby/l.title/direction/asc/" rel="nofollow"><?php echo $this->_tpl_vars['LANG_TITLE']; ?>
</a></th>
				<?php else: ?>
					<th class="content_field_header_cell"><a title="<?php echo $this->_tpl_vars['LANG_SORT_ASCENDING']; ?>
" href="<?php echo $this->_tpl_vars['order_url']; ?>
orderby/l.title/direction/desc/" rel="nofollow"><?php echo $this->_tpl_vars['LANG_TITLE']; ?>
</a></th>
				<?php endif; ?>
			<?php else: ?>
				<th class="content_field_header_cell"><?php echo $this->_tpl_vars['LANG_TITLE']; ?>
</th>
			<?php endif; ?>
			
			<?php $_from = $this->_tpl_vars['content_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field'] => $this->_tpl_vars['seo_name']):
?>
				<?php $this->assign('field_object', $this->_tpl_vars['VH']->unserialize($this->_tpl_vars['field'])); ?>
				<?php $this->assign('field_orderby', $this->_tpl_vars['field_object']->field->seo_name); ?>
				
				<?php if ($this->_tpl_vars['field_object']->order_option && $this->_tpl_vars['order_url'] !== null): ?>
					<th class="content_field_header_cell">
					<?php if ($this->_tpl_vars['orderby'] == ((is_array($_tmp="cf.")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['field_orderby']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['field_orderby'])) && $this->_tpl_vars['direction'] == 'asc'): ?>
						<a class="descending" title="<?php echo $this->_tpl_vars['LANG_SORT_DESCENDING']; ?>
" href="<?php echo $this->_tpl_vars['order_url']; ?>
orderby/cf.<?php echo $this->_tpl_vars['field_orderby']; ?>
/direction/desc/" rel="nofollow">
					<?php elseif ($this->_tpl_vars['orderby'] == ((is_array($_tmp="cf.")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['field_orderby']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['field_orderby'])) && $this->_tpl_vars['direction'] == 'desc'): ?>
						<a class="ascending" title="<?php echo $this->_tpl_vars['LANG_SORT_ASCENDING']; ?>
" href="<?php echo $this->_tpl_vars['order_url']; ?>
orderby/cf.<?php echo $this->_tpl_vars['field_orderby']; ?>
/direction/asc/" rel="nofollow">
					<?php else: ?>
						<a title="<?php echo $this->_tpl_vars['LANG_SORT_DESCENDING']; ?>
" href="<?php echo $this->_tpl_vars['order_url']; ?>
orderby/cf.<?php echo $this->_tpl_vars['field_orderby']; ?>
/direction/desc/" rel="nofollow">
					<?php endif; ?>
					<?php if ($this->_tpl_vars['field_object']->field->frontend_name): ?><?php echo $this->_tpl_vars['field_object']->field->frontend_name; ?>
<?php else: ?><?php echo $this->_tpl_vars['field_object']->field->name; ?>
<?php endif; ?></a></th>
				<?php else: ?>
					<th class="content_field_header_cell"><?php if ($this->_tpl_vars['field_object']->field->frontend_name): ?><?php echo $this->_tpl_vars['field_object']->field->frontend_name; ?>
<?php else: ?><?php echo $this->_tpl_vars['field_object']->field->name; ?>
<?php endif; ?></th>
				<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
			
			<?php $this->assign('counter', 0); ?>
			<?php $_from = $this->_tpl_vars['listings_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['listing']):
?>
				<?php $this->assign('counter', $this->_tpl_vars['counter']+1); ?>
				<tr class="<?php if ($this->_tpl_vars['counter']%2): ?>even<?php else: ?>odd<?php endif; ?>" id="listing_id-<?php echo $this->_tpl_vars['listing']->getUniqueId(); ?>
"><?php echo $this->_tpl_vars['listing']->view('short'); ?>
</tr>
			<?php endforeach; endif; unset($_from); ?>

		</tr>
	</table>
</div>