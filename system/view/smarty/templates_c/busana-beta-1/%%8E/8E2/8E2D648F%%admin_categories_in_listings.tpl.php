<?php /* Smarty version 2.6.26, created on 2012-02-06 04:25:24
         compiled from backend/blocks/admin_categories_in_listings.tpl */ ?>
<?php if ($this->_tpl_vars['categories_tree']): ?>
<script language="javascript" type="text/javascript">
var ajax_categories_url = "<?php echo $this->_tpl_vars['VH']->site_url('ajax/categories_request/backend/categories/admin_categories_in_listings.tpl'); ?>
";
var stat =  [
<?php ob_start();
$_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'backend/categories/admin_categories_in_listings.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
$this->assign('template', ob_get_contents()); ob_end_clean();
 ?>
<?php $this->assign('i', 0); ?>
<?php $_from = $this->_tpl_vars['categories_tree']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['category']):
?>
	<?php echo $this->_tpl_vars['category']->render($this->_tpl_vars['template'],false,$this->_tpl_vars['max_depth'],null,'',', state : "closed"'); ?>

<?php endforeach; endif; unset($_from); ?>
];

jQuery( function($) {
	function nesting() {
		$("#categories_tree_wrap").jstree({
			"themes" : {
				"theme" : "classic",
				"url" : "<?php echo $this->_tpl_vars['smarty_obj']->getFileInTheme('css/jsTree_themes_v10/classic/style.css'); ?>
",
				"icons" : true
			},
			"json_data" : {
				"data" : stat,
				"ajax" : {
					"url" : ajax_categories_url,
					"data" : function (n) {
						return { id : ($(n).attr("id")+"").replace('category_in_listing_', ''), is_children_label : ', "state" : "closed"' };
					},
					"type" : "post"
				},
				"progressive_render" : true
			},
			"core" : {
					"html_titles" : true,
					"animation" : 100
			},
			"plugins" : ["themes", "json_data"]
		});
	}
	
	nesting();
});
</script>

<div id="categories_tree_wrap"></div>
<?php else: ?>
Create categories first!
<?php endif; ?>