<?php /* Smarty version 2.6.26, created on 2012-02-06 04:33:34
         compiled from content_fields/admin_manage_fields_in_group.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
var ajax_save_fields_list_url = '<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/groups/manage_fields/".($this->_tpl_vars['group_id'])."/save/"); ?>
';

$(document).ready(function(){
		$(".sortable").sortable({
        		connectWith: ".sortable",
        		items: 'li.drag',
        		placeholder: 'drop_highlight',
        		forcePlaceholderSize: true
		});

		$("ul, li").disableSelection();	

        $("#save").click(function() {
        		ajax_loader_show("<?php echo $this->_tpl_vars['LANG_FIELDS_SAVE_MSG']; ?>
");
                $.post(ajax_save_fields_list_url, {serialized_list: $('#serialized').sortable('serialize')}, function(data) {
                	ajax_loader_hide();
                });
        });
});
</script> 

                <div class="content">
                     <h3><?php echo $this->_tpl_vars['LANG_MANAGE_CONTENT_FIELDS_GROUP']; ?>
 "<?php echo $this->_tpl_vars['group_name']; ?>
"</h3>
                     <h4><?php echo $this->_tpl_vars['LANG_DONT_FORGET_CHANGES']; ?>
</h4>
                     
                     <div class="clear_float"></div>
                     <ul class="sortable" style="float: left;">
                       <li class="drop_title">
                       		<?php echo $this->_tpl_vars['LANG_ALL_CONTENT_FIELDS']; ?>

                       </li>
                       <?php $_from = $this->_tpl_vars['free_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
                       <?php $this->assign('field_id', $this->_tpl_vars['field']['id']); ?>
                       <li id="field_<?php echo $this->_tpl_vars['field']['id']; ?>
" class="drag">
                       	 	<?php echo $this->_tpl_vars['field']['name']; ?>

                       	 	<div class="field_type"><?php echo $this->_tpl_vars['field']['type_name']; ?>
</div>
							<?php if ($this->_tpl_vars['field']['configuration_page']): ?>
							<div class="field_options_link"><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/configure/".($this->_tpl_vars['field_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_CONFIGURE_FIELD_OPTION']; ?>
</a>&nbsp;&nbsp;
							<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/edit/".($this->_tpl_vars['field_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_EDIT_FIELD_OPTION']; ?>
</a></div>
							<?php endif; ?>
                       </li>
                       <?php endforeach; endif; unset($_from); ?>
                     </ul>
                     <ul class="sortable" id="serialized" style="float: left;">
                       <li class="drop_title">
                       		<?php echo $this->_tpl_vars['LANG_FIELDS_OF_GROUP']; ?>

                       </li>
                       <?php $_from = $this->_tpl_vars['fields_of_group']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field']):
?>
                       <?php $this->assign('field_id', $this->_tpl_vars['field']['id']); ?>
                       <li id="field_<?php echo $this->_tpl_vars['field']['id']; ?>
" class="drag">
                       	 	<?php echo $this->_tpl_vars['field']['name']; ?>

                       	 	<div class="field_type"><?php echo $this->_tpl_vars['field']['type_name']; ?>
</div>
                       	 	<?php if ($this->_tpl_vars['field']['configuration_page']): ?>
							<div class="field_options_link"><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/configure/".($this->_tpl_vars['field_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_CONFIGURE_FIELD_OPTION']; ?>
</a>&nbsp;&nbsp;
							<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/edit/".($this->_tpl_vars['field_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_EDIT_FIELD_OPTION']; ?>
</a></div>
							<?php endif; ?>
                       </li>
                       <?php endforeach; endif; unset($_from); ?>
                     </ul>

                     <div class="clear_float"></div>
                     
                     <br />
                     <br />
                     <input class="button save_button" type=button id="save" name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
" />
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>