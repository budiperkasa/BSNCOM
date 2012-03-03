					<div class="admin_option">
						<div class="admin_option_name">
							{$title}
						</div>
						<div class="admin_option_description">
							{$LANG_MAX_IMAGE_SIZE} {$attrs.width}*{$attrs.height}px, {$LANG_MAX_FILE_SIZE} {$max_upload_filesize}. {$LANG_SUPPORTED_FORMAT}: {$VH->str_replace('|', ', ', $allowed_types)}
						</div>
						<div id="img_wrapper">
							<div id="img_div_border" style="{if !$current_file}display:none; {/if}width: {$attrs.width}px; height: {$attrs.height}px;">
								<div id="img_div" style="width: {$attrs.width}px; height: {$attrs.height}px;">
									<table width="{$attrs.width+4}px" height="{$attrs.height+4}px">
										<tr>
											<td valign="middle" align="center">
												<img id="img" src="{if $current_file}{$upload_to}{$current_file}{/if}"/>
											</td>
										</tr>
									</table>
									<input type="hidden" name="{$upload_id}" id="{$upload_id}" value="{if $current_file}{$current_file}{/if}">
								</div>
							</div>
							<input id="{$upload_id}_browse" type="file" size="45" name="{$upload_id}_browse"><br />
							<label><input type="checkbox" value="1" name="crop"> {$LANG_CROP_IMAGE}</label>
							<input type="button" class="upload_button button" onclick="return ajaxImageFileUpload('{$upload_id}', '{$VH->site_url("ajax/files_upload/$upload_id")}', '{$after_upload_url}', '{$upload_to}', '{$error_file_choose}');" value="{$LANG_BUTTON_UPLOAD_IMAGE}">
						</div>
						<img id="loading" src="{$public_path}images/ajax-loader.gif" style="display: none;">
					</div>