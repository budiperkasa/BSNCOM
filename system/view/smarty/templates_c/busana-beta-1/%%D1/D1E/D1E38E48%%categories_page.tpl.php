<?php /* Smarty version 2.6.26, created on 2012-03-05 07:05:56
         compiled from frontend/categories_page.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'render_frontend_block', 'frontend/categories_page.tpl', 15, false),array('modifier', 'count', 'frontend/categories_page.tpl', 32, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

			<tr>
				<td id="search_bar" colspan="3">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/search_block.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				</td>
			</tr>
			<tr>
				<td id="left_sidebar">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/left-sidebar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				</td>
      			<td id="content_block" valign="top">
      				<div id="content_wrapper">

				      	<?php echo smarty_function_render_frontend_block(array('block_type' => 'map_and_markers','block_template' => 'frontend/blocks/map_standart.tpl','existed_listings' => $this->_tpl_vars['listings'],'clasterization' => false), $this);?>


      					<div class="breadcrumbs">
      						<a href="<?php echo $this->_tpl_vars['VH']->index_url(); ?>
"><?php echo $this->_tpl_vars['LANG_HOME_PAGE']; ?>
</a><?php $_from = $this->_tpl_vars['breadcrumbs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['source_url'] => $this->_tpl_vars['source_page']):
?> » <a href="<?php echo $this->_tpl_vars['source_url']; ?>
"><?php echo $this->_tpl_vars['source_page']; ?>
</a><?php endforeach; endif; unset($_from); ?> » <span><?php echo $this->_tpl_vars['current_category']->getChainAsLinks(); ?>
</span>
      						<?php if ($this->_tpl_vars['CI']->load->is_module_loaded('rss')): ?>
                        		<div class="rss_icon"">
                        			<a href="<?php echo $this->_tpl_vars['VH']->getRssUrl(); ?>
" title="<?php echo $this->_tpl_vars['VH']->getRssTitle(); ?>
">
                        				<nobr><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/feed.png" />&nbsp;<img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/rss.png" /></nobr>
                        			</a>
                        		</div>
                        	<?php endif; ?>
      					</div>

	      				<?php if (count($this->_tpl_vars['current_category']->children)): ?>
						<h1><?php echo $this->_tpl_vars['LANG_SUBCATEGORIES']; ?>
</h1>
                        <div class="subcategories_list">
                        	<?php $_from = $this->_tpl_vars['current_category']->children; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['category']):
?>
                        	<?php $this->assign('subcategory_id', $this->_tpl_vars['category']->id); ?>
                        		<span class="subcategory_item">
                        			<a href="<?php echo $this->_tpl_vars['VH']->site_url($this->_tpl_vars['category']->getUrl()); ?>
" class="subcategory"><?php echo $this->_tpl_vars['category']->name; ?>
&nbsp;(<?php echo $this->_tpl_vars['category']->countListings(); ?>
)</a>&nbsp;&nbsp;
                        		</span>
                        	<?php endforeach; endif; unset($_from); ?>
                        	<div class="clear_float"></div>
                        </div>
                        <?php endif; ?>

                        <?php echo smarty_function_render_frontend_block(array('block_type' => 'listings','block_template' => 'frontend/blocks/with_paginator.tpl','items_array' => $this->_tpl_vars['listings'],'view_name' => $this->_tpl_vars['view']->view,'view_format' => $this->_tpl_vars['view']->format,'listings_paginator' => $this->_tpl_vars['listings_paginator'],'order_url' => $this->_tpl_vars['order_url'],'orderby' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction']), $this);?>

                 	</div>
                </td>
                <td id="right_sidebar">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/right-sidebar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
			</tr>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>