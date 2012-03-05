<?php /* Smarty version 2.6.26, created on 2012-02-09 05:47:50
         compiled from backend/blocks/admin_locations_management.tpl */ ?>
<?php if ($this->_tpl_vars['locations_tree']): ?>
<?php $this->assign('loc_levels_count', $this->_tpl_vars['CI']->locations->getLocationsLevelsCount()); ?>
<?php $this->assign('curr_levels_count', 1); ?>
<?php $this->assign('labeled_locations', $this->_tpl_vars['CI']->locations->selectLocationsFromDB(true,true)); ?>
<script language="javascript" type="text/javascript">
jQuery( function($) {
	var ajax_locations_url = "<?php echo $this->_tpl_vars['VH']->site_url('ajax/locations_request/backend/locations/admin_management.tpl'); ?>
";
	var locations_stat =  [
	<?php ob_start();
$_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'backend/locations/admin_management.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
$this->assign('template', ob_get_contents()); ob_end_clean();
 ?>
	<?php $_from = $this->_tpl_vars['locations_tree']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['location']):
?>
		<?php echo $this->_tpl_vars['location']->render($this->_tpl_vars['template'],false,$this->_tpl_vars['max_depth'],$this->_tpl_vars['labeled_locations'],'class=\"bold\"',', state : "closed"',$this->_tpl_vars['is_only_labeled']); ?>

	<?php endforeach; endif; unset($_from); ?>
	];
	
	function liSerialize() {
		serialized = '';
		$("#locations_list > ul > li").each(function() {
			if ($(this).attr('id')) {
				serialized += '*[' + ($(this).attr("id")+"").replace('location_', '') + ']';
				recursiveLiSerialize(this);
			}
		});
		$('#list').attr('value', serialized);
	}
	
	function recursiveLiSerialize(li) {
		$("#" + $(li).attr('id') + " > ul > li").each(function() {
			if ($(this).attr('id'))
			    serialized += '*[' + ($(li).attr("id")+"").replace('location_', '') + '-' + ($(this).attr("id")+"").replace('location_', '') + ']';
			recursiveLiSerialize(this);
		});
	}
	
	function checkedSerialize() {
		var checked_list = [];
		$.jstree._reference("#locations_list").get_checked().each(function() {
			checked_list.push(($(this).attr("id")+"").replace('location_', ''));
		});
		$('#checked_list').attr('value', checked_list.join('*'));
	}
	
	function nesting() {
		$("#locations_list").jstree({
			"themes" : {
				"theme" : "classic",
				"url" : "<?php echo $this->_tpl_vars['smarty_obj']->getFileInTheme('css/jsTree_themes_v10/classic/style.css'); ?>
",
				"icons" : true
			},
			"json_data" : {
				"data" : locations_stat,
				"ajax" : {
					"url" : ajax_locations_url,
					"data" : function (n) {
						return { id : ($(n).attr("id")+"").replace('location_', ''), max_depth : '<?php echo $this->_tpl_vars['max_depth']; ?>
', selected_locations : '<?php echo $this->_tpl_vars['VH']->json_encode($this->_tpl_vars['labeled_locations']); ?>
', highlight_element : 'class=\\"bold\\"', is_children_label : ', "state" : "closed"', is_only_labeled : '<?php echo $this->_tpl_vars['is_only_labeled']; ?>
' };
					},
					"type" : "post"
				},
				"progressive_render" : true
			},
			"core" : {
					"html_titles" : true,
					"animation" : 100
			},
			"plugins" : ["themes", "json_data", "checkbox", "dnd", "types"],
			"checkbox" : { "two_state" : false },
			"types" : {
				"max_depth" : <?php echo $this->_tpl_vars['loc_levels_count']; ?>

			}
		}).bind("check_node.jstree", function (e, data) {
			checkedSerialize();
		}).bind("uncheck_node.jstree", function (e, data) {
			checkedSerialize();
		}).bind("move_node.jstree", function (e, data) {
			liSerialize();
		});
	}

	nesting();
	liSerialize();
});
</script>

<input type="hidden" id="list" name="list">
<input type="hidden" id="checked_list" name="checked_list">
<div id="locations_list"></div>
<?php endif; ?>