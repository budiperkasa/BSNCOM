<?php /* Smarty version 2.6.26, created on 2012-03-05 07:21:52
         compiled from frontend/blocks/categories_search_block_ajax.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'frontend/blocks/categories_search_block_ajax.tpl', 4, false),)), $this); ?>

<!-- CATEGORIES BLOCK -->

				<?php if (count($this->_tpl_vars['categories_tree'])): ?>
					<div id="search_by_category_tree"></div>
					
					<script language="javascript" type="text/javascript">
						// JsTree v1.0 version
						$("#search_by_category_tree").jstree({
							"themes" : {
								"theme" : "classic",
								"url" : "<?php echo $this->_tpl_vars['smarty_obj']->getFileInTheme('css/jsTree_themes_v10/classic/style.css'); ?>
",
								"icons" : true
							},
							"json_data" : {
								"data" : [
								<?php ob_start();
$_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'frontend/categories/search_ajax.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
$this->assign('template', ob_get_contents()); ob_end_clean();
 ?>
								<?php $_from = $this->_tpl_vars['categories_tree']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['category']):
?>
									<?php echo $this->_tpl_vars['category']->render($this->_tpl_vars['template'],$this->_tpl_vars['is_counter'],$this->_tpl_vars['max_depth'],$this->_tpl_vars['search_categories_array'],' "checked" : "true"',',"state" : "closed"'); ?>
,
								<?php endforeach; endif; unset($_from); ?>
								],
								"ajax" : {
									"url" : "<?php echo $this->_tpl_vars['VH']->site_url("ajax/categories_request/frontend/categories/search_ajax.tpl"); ?>
",
									"data" : function (n) {
										return { id : $(n).attr ? $(n).attr("id").split("_")[1] : 0 , is_counter : '<?php echo $this->_tpl_vars['is_counter']; ?>
', max_depth : '<?php echo $this->_tpl_vars['max_depth']; ?>
', selected_categories : '<?php echo $this->_tpl_vars['VH']->json_encode($this->_tpl_vars['search_categories_array']); ?>
', highlight_element : ', "checked" : "true"', is_children_label : ', "state" : "closed"' };
									},
									"type" : "post"
								},
								"progressive_render" : true
							},
							"core" : {
									"html_titles" : true
							},
							"plugins" : ["themes","json_data","checkbox"]
						}).bind("loaded.jstree", function () {
							$('#search_by_category_tree li[checked=true]').each(function() {
								$.jstree._reference("#search_by_category_tree").check_node($(this));
							});
						});
						<?php $_from = $this->_tpl_vars['search_categories_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['selected_category']):
?>
							<?php if ($this->_tpl_vars['VH']->is_numeric($this->_tpl_vars['selected_category'])): ?>
								//$("#search_by_category_tree").jstree("check_node", $("#search_<?php echo $this->_tpl_vars['selected_category']; ?>
"));
							<?php elseif ($this->_tpl_vars['VH']->is_object($this->_tpl_vars['selected_category'])): ?>
								//$("#search_by_category_tree").jstree("check_node", $("#search_<?php echo $this->_tpl_vars['selected_category']->id; ?>
"));
							<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?>
					</script>
				<?php endif; ?>

<!-- /CATEGORIES BLOCK -->