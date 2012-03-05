<?php /* Smarty version 2.6.26, created on 2012-02-06 03:22:47
         compiled from backend/blocks/admin_categories_management.tpl */ ?>
<?php if ($this->_tpl_vars['categories_tree']): ?>
<script language="javascript" type="text/javascript">
var ajax_categories_url = "<?php echo $this->_tpl_vars['VH']->site_url('ajax/categories_request/backend/categories/admin_management.tpl'); ?>
";
var stat =  [
<?php ob_start();
$_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'backend/categories/admin_management.tpl', 'smarty_include_vars' => array()));
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
	function liSerialize() {
		serialized = '';
		$("#categories_list > ul > li").each(function() {
			if ($(this).attr('id')) {
				serialized += '*[' + ($(this).attr("id")+"").replace('category_', '') + ']';
				recursiveLiSerialize(this);
			}
		});
		$('#list').attr('value', serialized);
	}
	
	function recursiveLiSerialize(li) {
		$("#" + $(li).attr('id') + " > ul > li").each(function() {
			if ($(this).attr('id'))
			    serialized += '*[' + ($(li).attr("id")+"").replace('category_', '') + '-' + ($(this).attr("id")+"").replace('category_', '') + ']';
			recursiveLiSerialize(this);
		});
	}

	function nesting() {
		$("#categories_list").jstree({
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
						return { id : ($(n).attr("id")+"").replace('category_', ''), max_depth : '<?php echo $this->_tpl_vars['max_depth']; ?>
', is_children_label : ', "state" : "closed"' };
					},
					"type" : "post"
				},
				"progressive_render" : true
			},
			"core" : {
					"html_titles" : true,
					"animation" : 100
			},
			"plugins" : ["themes", "json_data", "dnd"]
		}).bind("move_node.jstree", function (e, data) {
			liSerialize();
		});
	}
	
	nesting();
	liSerialize();
});
</script>

<input type="hidden" id="list" name="list">
<div id="categories_list"></div>
<?php endif; ?>