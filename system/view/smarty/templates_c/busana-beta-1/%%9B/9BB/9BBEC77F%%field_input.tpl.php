<?php /* Smarty version 2.6.26, created on 2012-02-06 04:34:11
         compiled from content_fields/fields/datetime/field_input.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'content_fields/fields/datetime/field_input.tpl', 5, false),)), $this); ?>
			<div class="admin_option_name">
				<?php echo $this->_tpl_vars['field']->name; ?>
<?php if ($this->_tpl_vars['field']->required): ?><span class="red_asterisk">*</span><?php endif; ?>
			</div>
			<div class="admin_option_description">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['field']->description)) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

			</div>
			<script language="javascript" type="text/javascript">
                    $(document).ready( function() {
                        $("#<?php echo $this->_tpl_vars['field']->seo_name; ?>
").datepicker({
                            showOn: "both",
                            buttonImage: "<?php echo $this->_tpl_vars['public_path']; ?>
images/calendar.png",
                            buttonImageOnly: true,
                            showButtonPanel: true,
							closeText: 'Clear',
                            onSelect: function(dateText) {
                            	var sDate = $("#<?php echo $this->_tpl_vars['field']->seo_name; ?>
").datepicker("getDate");
                            	if (sDate) {
									sDate.setMinutes(sDate.getMinutes() - sDate.getTimezoneOffset());
									tmstmp_str = $.datepicker.formatDate('@', sDate)/1000;
								} else 
									tmstmp_str = 0;

                        		$("#tmstmp_<?php echo $this->_tpl_vars['field']->seo_name; ?>
").val(tmstmp_str);
                        	}
                        });
                        $("#<?php echo $this->_tpl_vars['field']->seo_name; ?>
").datepicker("option", $.datepicker.regional["<?php echo $this->_tpl_vars['current_language']; ?>
"]);

                        <?php if ($this->_tpl_vars['date'] != '' && $this->_tpl_vars['date'] != '0000-00-00 00:00:00' && $this->_tpl_vars['date'] != '1970-01-01 00:00:00'): ?>
                        	$("#<?php echo $this->_tpl_vars['field']->seo_name; ?>
").datepicker('setDate', $.datepicker.parseDate('yy-mm-dd', '<?php echo $this->_tpl_vars['date']; ?>
'));
                        <?php endif; ?>
                    });
			</script>
			<?php echo $this->_tpl_vars['LANG_DATE']; ?>
:&nbsp;
			<input type="text" size="10" value="" name="field_<?php echo $this->_tpl_vars['field']->seo_name; ?>
" id="<?php echo $this->_tpl_vars['field']->seo_name; ?>
"/>&nbsp;&nbsp;
			<input type="hidden" id="tmstmp_<?php echo $this->_tpl_vars['field']->seo_name; ?>
" name="tmstmp_<?php echo $this->_tpl_vars['field']->seo_name; ?>
" value="<?php echo $this->_tpl_vars['date_tmstmp']; ?>
">
			<?php if ($this->_tpl_vars['enable_time']): ?>
			<?php echo $this->_tpl_vars['LANG_TIME']; ?>
:&nbsp;
			<input type="text" size="1" value="<?php echo $this->_tpl_vars['time_h']; ?>
" maxlength="2" name="h_<?php echo $this->_tpl_vars['field']->seo_name; ?>
">&nbsp;:
			<input type="text" size="1" value="<?php echo $this->_tpl_vars['time_m']; ?>
" maxlength="2" name="m_<?php echo $this->_tpl_vars['field']->seo_name; ?>
">&nbsp;:
			<input type="text" size="1" value="<?php echo $this->_tpl_vars['time_s']; ?>
" maxlength="2" name="s_<?php echo $this->_tpl_vars['field']->seo_name; ?>
">
			<?php else: ?>
			<input type="hidden" value="00" name="h_<?php echo $this->_tpl_vars['field']->seo_name; ?>
">
			<input type="hidden" value="00" name="m_<?php echo $this->_tpl_vars['field']->seo_name; ?>
">
			<input type="hidden" value="00" name="s_<?php echo $this->_tpl_vars['field']->seo_name; ?>
">
			<?php endif; ?>
			<br />
			<br />