<?php /* Smarty version 2.6.26, created on 2012-03-05 07:12:37
         compiled from frontend/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'render_frontend_block', 'frontend/index.tpl', 49, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

			<tr>			  
			<td id="kolom_atas"colspan="3">
				 <div id="kolom_kanan">
				   <div class="site_desc"><h1><?php echo $this->_tpl_vars['site_settings']['description']; ?>
</h1>
				       <p><?php echo $this->_tpl_vars['site_settings']['website_title']; ?>
</p>
				   </div>
				   <div class="search_bar">				  
				     <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/search_block.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				   </div>	
				   <div class="video_action">
				     <div class="video">
					       <iframe src="http://player.vimeo.com/video/36276188?title=0&amp;byline=0&amp;portrait=0" width="200" height="108" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
					 </div>
					 <div class="action">
					   <div class="content_field_output">
					     <h2>Kenapa harus busana.com ?.</h2>
					   </div>
					   <div class="content_field_output">
						 <p>Karena Busana.com satu-satunya direktori website pertama kali di Indonesia yang melisting website-website busana. </p>
					   </div>
					 </div>
				   </div>			  
				 </div>	
				 <div id="kolom_kiri">
				    <div class="slideshow">
				      
					      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/slideshow.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				      
					</div>
				 </div>			
			</td>
				
			</tr>
			<tr>
			    <td id="left_sidebar"></td>
				<td id="content_block" valign="top"></td>
				<td id="right_sidebar"></td>		
			</tr>
			<tr>
				<td id="left_sidebar_front">
				 <div class="modul_kiri">
				   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/left-sidebar-front.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
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
				 <div class="px5"></div>				
				</td>
      			<td id="content_block_front" valign="top">
				 <div class="modul_tengah">
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
				 <div class="px5"></div>      				
                </td>
                <td id="right_sidebar_front">
				 <div class="modul_kanan">
				   <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/right-sidebar-front.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
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
				 <div class="px5"></div>
                </td>
			</tr>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>