<?php /* Smarty version 2.6.26, created on 2012-02-06 03:58:57
         compiled from files_upload/user_logo_upload_block.tpl */ ?>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['title']; ?>

						</div>
						<div class="admin_option_description">
							<?php echo $this->_tpl_vars['LANG_MAX_IMAGE_SIZE']; ?>
 <?php echo $this->_tpl_vars['attrs']['width']; ?>
*<?php echo $this->_tpl_vars['attrs']['height']; ?>
px, <?php echo $this->_tpl_vars['LANG_MAX_FILE_SIZE']; ?>
 <?php echo $this->_tpl_vars['max_upload_filesize']; ?>
. <?php echo $this->_tpl_vars['LANG_SUPPORTED_FORMAT']; ?>
: <?php echo $this->_tpl_vars['VH']->str_replace('|',', ',$this->_tpl_vars['allowed_types']); ?>

						</div>
						<div id="img_wrapper">
							<div id="img_div_border" style="<?php if (! $this->_tpl_vars['current_file']): ?>display:none; <?php endif; ?>width: <?php echo $this->_tpl_vars['attrs']['width']; ?>
px; height: <?php echo $this->_tpl_vars['attrs']['height']; ?>
px;">
								<div id="img_div" style="width: <?php echo $this->_tpl_vars['attrs']['width']; ?>
px; height: <?php echo $this->_tpl_vars['attrs']['height']; ?>
px;">
									<table width="<?php echo $this->_tpl_vars['attrs']['width']+4; ?>
px" height="<?php echo $this->_tpl_vars['attrs']['height']+4; ?>
px">
										<tr>
											<td valign="middle" align="center">
												<img id="img" src="<?php if ($this->_tpl_vars['current_file']): ?><?php echo $this->_tpl_vars['upload_to']; ?>
<?php echo $this->_tpl_vars['current_file']; ?>
<?php endif; ?>"/>
											</td>
										</tr>
									</table>
									<input type="hidden" name="<?php echo $this->_tpl_vars['upload_id']; ?>
" id="<?php echo $this->_tpl_vars['upload_id']; ?>
" value="<?php if ($this->_tpl_vars['current_file']): ?><?php echo $this->_tpl_vars['current_file']; ?>
<?php endif; ?>">
								</div>
							</div>
							<input id="<?php echo $this->_tpl_vars['upload_id']; ?>
_browse" type="file" size="45" name="<?php echo $this->_tpl_vars['upload_id']; ?>
_browse"><br />
							<label><input type="checkbox" value="1" name="crop"> <?php echo $this->_tpl_vars['LANG_CROP_IMAGE']; ?>
</label>
							<input type="button" class="upload_button button" onclick="return ajaxImageFileUpload('<?php echo $this->_tpl_vars['upload_id']; ?>
', '<?php echo $this->_tpl_vars['VH']->site_url("ajax/files_upload/".($this->_tpl_vars['upload_id'])); ?>
', '<?php echo $this->_tpl_vars['after_upload_url']; ?>
', '<?php echo $this->_tpl_vars['upload_to']; ?>
', '<?php echo $this->_tpl_vars['error_file_choose']; ?>
');" value="<?php echo $this->_tpl_vars['LANG_BUTTON_UPLOAD_IMAGE']; ?>
">
						</div>
						<img id="loading" src="<?php echo $this->_tpl_vars['public_path']; ?>
images/ajax-loader.gif" style="display: none;">
					</div>