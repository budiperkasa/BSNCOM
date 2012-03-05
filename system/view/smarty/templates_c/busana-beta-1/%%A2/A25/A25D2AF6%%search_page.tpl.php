<?php /* Smarty version 2.6.26, created on 2012-03-05 06:52:27
         compiled from frontend/search_page.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'render_frontend_block', 'frontend/search_page.tpl', 13, false),array('modifier', 'count', 'frontend/search_page.tpl', 30, false),)), $this); ?>
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
				
      			<td id="content_block" valign="top">
      				<div id="content_wrapper">

      					<?php echo smarty_function_render_frontend_block(array('block_type' => 'map_and_markers','block_template' => 'frontend/blocks/map_standart.tpl','existed_listings' => $this->_tpl_vars['listings'],'clasterization' => false), $this);?>


      					<div class="breadcrumbs">
      						<a href="<?php echo $this->_tpl_vars['VH']->index_url(); ?>
"><?php echo $this->_tpl_vars['LANG_HOME_PAGE']; ?>
</a> » <span><?php echo $this->_tpl_vars['LANG_BREADCRUMBS_SEARCH']; ?>
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
      				
						<?php if (count($this->_tpl_vars['listings'])): ?>
							<?php echo smarty_function_render_frontend_block(array('block_type' => 'listings','block_template' => 'frontend/blocks/with_paginator.tpl','search_args' => $this->_tpl_vars['args'],'items_array' => $this->_tpl_vars['listings'],'view_name' => $this->_tpl_vars['view']->view,'view_format' => $this->_tpl_vars['view']->format,'type' => $this->_tpl_vars['type'],'listings_paginator' => $this->_tpl_vars['listings_paginator'],'order_url' => $this->_tpl_vars['order_url'],'orderby' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction']), $this);?>

                        <?php else: ?>
							<ul>
								<li><?php echo $this->_tpl_vars['LANG_NO_SEARCH']; ?>
</li>
							</ul>
						<?php endif; ?>
                 	</div>
                </td>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>