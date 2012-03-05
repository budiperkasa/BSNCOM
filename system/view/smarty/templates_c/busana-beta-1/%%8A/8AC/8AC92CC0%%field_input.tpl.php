<?php /* Smarty version 2.6.26, created on 2012-02-06 09:25:23
         compiled from content_fields/fields/richtext/field_input.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'translate_content', 'content_fields/fields/richtext/field_input.tpl', 5, false),array('modifier', 'nl2br', 'content_fields/fields/richtext/field_input.tpl', 8, false),)), $this); ?>
<?php $this->assign('field_value_id', $this->_tpl_vars['field']->field_value_id); ?>

			<div class="admin_option_name">
				<?php echo $this->_tpl_vars['field']->name; ?>
<?php if ($this->_tpl_vars['field']->required): ?><span class="red_asterisk">*</span><?php endif; ?>
				<?php echo smarty_function_translate_content(array('table' => 'content_fields_type_richtext_data','field' => 'field_value','row_id' => $this->_tpl_vars['field_value_id'],'field_type' => 'richtext'), $this);?>

			</div>
			<div class="admin_option_description">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['field']->description)) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

			</div>
			<?php echo $this->_tpl_vars['value']; ?>

			<br />
			<br />