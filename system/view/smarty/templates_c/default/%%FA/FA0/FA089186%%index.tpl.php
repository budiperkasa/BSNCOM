<?php /* Smarty version 2.6.26, created on 2012-02-06 02:23:49
         compiled from frontend/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'render_frontend_block', 'frontend/index.tpl', 28, false),)), $this); ?>
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
      					<?php if ($this->_tpl_vars['CI']->load->is_module_loaded('rss')): ?>
                        	<h1 id="index_header">
                        		<div class="rss_icon_index"">
                        			<a href="<?php echo $this->_tpl_vars['VH']->getRssUrl(); ?>
" title="<?php echo $this->_tpl_vars['VH']->getRssTitle(); ?>
">
                        				<nobr><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/feed.png" />&nbsp;<img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/rss.png" /></nobr>
                        			</a>
                        		</div>
                        	</h1>
                        <?php endif; ?>

                        <div class="index_listings">
                        	<?php $_from = $this->_tpl_vars['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type']):
?>
                        		<?php $this->assign('type_id', $this->_tpl_vars['type']->id); ?>
                        		<?php $this->assign('view', $this->_tpl_vars['listings_views']->getViewByTypeIdAndPage($this->_tpl_vars['type_id'],'index')); ?>
                        		<?php echo smarty_function_render_frontend_block(array('block_type' => 'listings','block_template' => 'frontend/blocks/for_index.tpl','items_array' => $this->_tpl_vars['listings_of_type'][$this->_tpl_vars['type_id']],'view_name' => $this->_tpl_vars['view']->view,'view_format' => $this->_tpl_vars['view']->format,'type' => $this->_tpl_vars['type']), $this);?>

                        	<?php endforeach; endif; unset($_from); ?>
                        </div>
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