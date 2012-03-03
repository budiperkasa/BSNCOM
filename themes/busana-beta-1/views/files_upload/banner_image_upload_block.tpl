{assign var="banner" value=$attrs.banner_obj}

					<div class="admin_option">
						<div class="admin_option_name">
							{$title}
						</div>
						<div class="admin_option_description">
							{$LANG_MAX_IMAGE_SIZE} {$banner->block->explodeSize('block_size', 0)}*{$banner->block->explodeSize('block_size', 1)}px, {$LANG_MAX_FILE_SIZE} {$max_upload_filesize}. {$LANG_SUPPORTED_FORMAT}: {$VH->str_replace('|', ', ', $allowed_types)}
						</div>
						<div id="img_wrapper">
							<div id="img_div_border" style="{if $banner->is_uploaded_flash || !$banner->banner_file}display:none; {/if}width: {$banner->block->explodeSize('block_size', 0)}px; height: {$banner->block->explodeSize('block_size', 1)}px;">
								<div id="img_div" style="width: {$banner->block->explodeSize('block_size', 0)}px; height: {$banner->block->explodeSize('block_size', 1)}px;">
									<table width="{$banner->block->explodeSize('block_size', 0)+4}px" height="{$banner->block->explodeSize('block_size', 1)+4}px">
										<tr>
											<td valign="middle" align="center">
											    <img id="img" src="{$users_content}banners/{$banner->banner_file}" />
											</td>
										</tr>
									</table>
								</div>
							</div>
							<div id="flash_div_border" style="{if !$banner->is_uploaded_flash || !$banner->banner_file}display:none;{/if}">
								<div id="flash_banner">
									<script language="javascript" type="text/javascript">
										swfobject.embedSWF("{$users_content}banners/{$banner->banner_file}", "flash_banner", "{$banner->block->explodeSize('block_size', 0)}", "{$banner->block->explodeSize('block_size', 1)}", "9.0.0");
									</script>
								</div>
							</div>
							<input type="hidden" name="{$upload_id}" id="{$upload_id}" value="{if $banner->banner_file}{$banner->banner_file}{/if}">
							<div class="px5"></div>
							<input id="{$upload_id}_browse" type="file" size="45" name="{$upload_id}_browse"><br />
							<div class="px5"></div>
							<input type="button" class="upload_button button" onclick="return ajaxBannerFileUpload('{$upload_id}', '{$VH->site_url("ajax/files_upload/$upload_id")}', '{$after_upload_url}', '{$upload_to}', '{$error_file_choose}', '{$banner->block->explodeSize('block_size', 0)}', '{$banner->block->explodeSize('block_size', 1)}');" value="{$LANG_BUTTON_UPLOAD_IMAGE}">
							<input type="hidden" name="is_uploaded_flash" id="is_uploaded_flash" {if $banner->is_uploaded_flash}value=1{/if} />
						</div>
						<img id="loading" src="{$public_path}images/ajax-loader.gif" style="display: none;">
					</div>