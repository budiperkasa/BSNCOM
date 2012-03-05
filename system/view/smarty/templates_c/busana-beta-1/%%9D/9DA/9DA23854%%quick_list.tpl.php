<?php /* Smarty version 2.6.26, created on 2012-02-21 09:53:05
         compiled from frontend/quick_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'addslashes', 'frontend/quick_list.tpl', 15, false),array('function', 'render_frontend_block', 'frontend/quick_list.tpl', 39, false),array('modifier', 'count', 'frontend/quick_list.tpl', 37, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script language="Javascript" type="text/javascript">
	$(document).ready(function() {
		$(".remove_favourite").click(function() {
			favourites_array = PHPSerializer.unserialize($.cookie("favourites"));

			id = $(this).attr('id');
			if ((key = in_array(id, favourites_array)) !== false) {
				favourites_array[key] = undefined;
				$.cookie("favourites", PHPSerializer.serialize(favourites_array), {expires: 365, path: "/"});
				$(this).parent().slideUp("normal", function() {
					$(this).remove();
				});
				$.jGrowl('<?php echo smarty_function_addslashes(array('string' => $this->_tpl_vars['LANG_QUICK_FROM_LIST_SUCCESS']), $this);?>
', {
					life: 3000,
					theme: 'jGrowl_success_msg'
				});
			}
			return false;
		});
	});
</script>

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

                        <h1><?php echo $this->_tpl_vars['LANG_QUICK_LIST']; ?>
 (<?php echo count($this->_tpl_vars['listings']); ?>
):</h1>
                        <div class="quicklist_listings">
                        	<?php echo smarty_function_render_frontend_block(array('block_type' => 'listings','block_template' => 'frontend/blocks/with_paginator.tpl','items_array' => $this->_tpl_vars['listings'],'view_name' => $this->_tpl_vars['view']->view,'view_format' => $this->_tpl_vars['view']->format,'listings_paginator' => $this->_tpl_vars['listings_paginator'],'order_url' => $this->_tpl_vars['order_url'],'orderby' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction']), $this);?>

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