<?php /* Smarty version 2.6.26, created on 2012-02-06 02:23:50
         compiled from frontend/blocks/categories-bar-ajax.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'frontend/blocks/categories-bar-ajax.tpl', 4, false),)), $this); ?>

<!-- CATEGORIES BLOCK -->

				<?php if (count($this->_tpl_vars['categories_tree'])): ?>
					<div class="block categories_block">
						<div class="block-top"><div class="block-top-title"><?php echo $this->_tpl_vars['LANG_CATEGORIES']; ?>
<?php if ($this->_tpl_vars['type']->categories_type == 'local'): ?> <?php echo $this->_tpl_vars['LANG_IN']; ?>
 <?php echo $this->_tpl_vars['type']->name; ?>
<?php endif; ?></div></div>
						<div class="block-bottom">
							<div id="tree" style="overflow:hidden"></div>
						</div>
					</div>
					<script language="javascript" type="text/javascript">
						$(document).ready(function() {
							// JsTree v1.0 version
			                $("#tree").jstree({
								"themes" : {
									"theme" : "classic",
									"url" : "<?php echo $this->_tpl_vars['smarty_obj']->getFileInTheme('css/jsTree_themes_v10/classic/style.css'); ?>
",
									"icons" : false
								},
								"json_data" : {
									"data" : [
									<?php ob_start();
$_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'frontend/categories/ajax.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
$this->assign('template', ob_get_contents()); ob_end_clean();
 ?>
									<?php $_from = $this->_tpl_vars['categories_tree']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['category']):
?>
										<?php echo $this->_tpl_vars['category']->render($this->_tpl_vars['template'],$this->_tpl_vars['is_counter'],$this->_tpl_vars['max_depth'],$this->_tpl_vars['current_category']->seo_name,', "style" : "background: #FFFFCC"',',"state" : "closed"'); ?>
,
									<?php endforeach; endif; unset($_from); ?>
									],
									"ajax" : {
										"url" : "<?php echo $this->_tpl_vars['VH']->site_url("ajax/categories_request/frontend/categories/ajax.tpl"); ?>
",
										"data" : function (n) {
											$("#"+$(n).attr("id")+" a ins").css("display", "block");
											return { id : $(n).attr("id") ? $(n).attr("id").split("_")[1] : 0 , is_counter : '<?php echo $this->_tpl_vars['is_counter']; ?>
', max_depth : '<?php echo $this->_tpl_vars['max_depth']; ?>
' };
										},
										"success" : function (data) {
											$("#tree li a ins").hide();
										},
										"type" : "post"
									},
									"progressive_render" : true
								},
								"core" : {
									"html_titles" : true
								},
								"plugins" : ["themes","json_data"]
							});
						});
					</script>
				<?php endif; ?>

	                
<!-- /CATEGORIES BLOCK -->