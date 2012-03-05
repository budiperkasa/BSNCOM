<?php /* Smarty version 2.6.26, created on 2012-03-05 07:05:56
         compiled from frontend/blocks/with_paginator.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'asc_desc_insert', 'frontend/blocks/with_paginator.tpl', 8, false),array('modifier', 'count', 'frontend/blocks/with_paginator.tpl', 12, false),)), $this); ?>
<span class="paginator_found">
	<?php echo $this->_tpl_vars['LANG_PAGINATION_FOUND_1']; ?>
 <b><?php echo $this->_tpl_vars['listings_paginator']->count(); ?>
</b> <?php echo $this->_tpl_vars['LANG_PAGINATION_FOUND_2']; ?>
 | <?php echo $this->_tpl_vars['LANG_PAGINATION_FOUND_3']; ?>
 <b><?php echo $this->_tpl_vars['listings_paginator']->getPage(); ?>
</b> <?php echo $this->_tpl_vars['LANG_PAGINATION_FOUND_4']; ?>
 <b><?php echo $this->_tpl_vars['listings_paginator']->countPages(); ?>
</b> <?php echo $this->_tpl_vars['LANG_PAGINATION_FOUND_5']; ?>
</h1>
</span>
<div class="clear_float"></div>

<div class="paginator_orderby">
	<span class="orderby"><?php echo $this->_tpl_vars['LANG_SEARCH_ORDER_BY']; ?>
:</span>&nbsp;
	<?php echo smarty_function_asc_desc_insert(array('base_url' => $this->_tpl_vars['order_url'],'orderby' => 'l.creation_date','orderby_query' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction'],'title' => $this->_tpl_vars['LANG_SEARCH_CREATION_DATE']), $this);?>

	|
	<?php echo smarty_function_asc_desc_insert(array('base_url' => $this->_tpl_vars['order_url'],'orderby' => 'l.title','orderby_query' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction'],'title' => $this->_tpl_vars['LANG_SEARCH_LISTING_TITLE'],'default_direction' => 'asc'), $this);?>


	<?php if ($this->_tpl_vars['current_type'] && count($this->_tpl_vars['current_type']->buildLevels()) > 1): ?>
	|
	<?php echo smarty_function_asc_desc_insert(array('base_url' => $this->_tpl_vars['order_url'],'orderby' => 'lev.order_num','orderby_query' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction'],'title' => $this->_tpl_vars['LANG_SEARCH_INFO_VALUE'],'default_direction' => 'desc'), $this);?>

	<?php endif; ?>

	<?php if ($this->_tpl_vars['current_type'] && count($this->_tpl_vars['current_type']->buildLevels()) == 1): ?>
		<?php $this->assign('levels', $this->_tpl_vars['current_type']->buildLevels()); ?>
		<?php $this->assign('only_level', $this->_tpl_vars['levels'][0]); ?>
	<?php endif; ?>
	<?php if (! $this->_tpl_vars['current_type'] || ! $this->_tpl_vars['only_level'] || ( $this->_tpl_vars['only_level'] && $this->_tpl_vars['only_level']->ratings_enabled )): ?>
	|
	<?php echo smarty_function_asc_desc_insert(array('base_url' => $this->_tpl_vars['order_url'],'orderby' => 'rating','orderby_query' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction'],'title' => $this->_tpl_vars['LANG_SEARCH_RATING']), $this);?>

	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['search_args']['where_radius'] && $this->_tpl_vars['search_args']['where_search']): ?>
	|
	<?php echo smarty_function_asc_desc_insert(array('base_url' => $this->_tpl_vars['order_url'],'orderby' => 'distance','orderby_query' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction'],'title' => $this->_tpl_vars['LANG_SEARCH_DISTANCE'],'default_direction' => 'asc'), $this);?>

	<?php endif; ?>
</div>
<div class="paginator_select_page">
	<?php echo $this->_tpl_vars['listings_paginator']->selectPageBlock(); ?>

</div>
<div class="clear_float"></div>

<div class="listings_list">
	<?php if ($this->_tpl_vars['wrapper_object']): ?>
		<?php echo $this->_tpl_vars['wrapper_object']->render(); ?>

	<?php else: ?>
		<?php $_from = $this->_tpl_vars['items_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
			<?php echo $this->_tpl_vars['item']->view($this->_tpl_vars['view_name']); ?>

		<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
	<?php echo $this->_tpl_vars['listings_paginator']->placeLinksToHtml(); ?>

</div>