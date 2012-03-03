{assign var="listing_id" value=$attrs.listing_id}
{assign var="images_limit" value=$attrs.images_limit}

				<script language="JavaScript" type="text/javascript">
					function appendRowToTable(data) {ldelim}
						var str = "<tr><td><input type='checkbox' name='cb_" + data.image_id + "' value='" + data.image_id + "'>" + "<" + "/td><td><a href='{$VH->site_url("admin/listings/images/edit/$listing_id/")}" + data.image_id + "'><img src='{$upload_to}../thmbs/" + data.file_name + "'><" + "/a>" + "<" + "/td><td><a href='{$VH->site_url("admin/listings/images/edit/$listing_id/")}" + data.image_id + "'>" + data.image_title + "<" + "/a>" + "<" + "/td><td>" + data.creation_date + "<" + "/td><td><a href='{$VH->site_url("admin/listings/images/edit/$listing_id/")}" + data.image_id + "' title='{$LANG_EDIT_FILE_TITLE|escape}'><img src='{$public_path}images/buttons/page_edit.png'></a>&nbsp;<a href='{$VH->site_url("admin/listings/images/delete/")}" + data.image_id + "' title='{$LANG_DELETE_FILE_TITLE|escape}'><img src='{$public_path}images/buttons/page_delete.png'></a>&nbsp;" + "<" + "/td>" + "<" + "/tr>";
						$(str).appendTo("#upload_to_this_block");
						
						var counter = parseInt($("#images_counter").html())+1;
						$("#images_counter").html(counter);
						if (counter < {$images_limit})
							return true;
						else
							return false;
					{rdelim}
				</script>

				<div id="upload_wrapper">
					<label class="block_title">{$LANG_UPLOAD_IMAGE}</label>
					<div class="admin_option">
						<div class="admin_option_name">	
							{$LANG_NEW_FILE_TITLE} <i>(255 {$LANG_SYMBOLS_MAX})</i>
							{translate_content table='images' field='title' row_id='new'}
						</div>
						<input type="text" id="title" size="60" name="title" />

						<div class="px10"></div>

						<div class="admin_option_name">
							{$title}<span class="red_asterisk">*</span>
						</div>
						<div class="admin_option_description">
							{$LANG_MAX_IMAGE_SIZE} {$attrs.width}*{$attrs.height}px, {$LANG_MAX_FILE_SIZE} {$max_upload_filesize}. {$LANG_SUPPORTED_FORMAT}: {$VH->str_replace('|', ', ', $allowed_types)}
						</div>
						<div id="img_wrapper">
							<input id="{$upload_id}_browse" type="file" size="45" name="{$upload_id}_browse"><br>
							<input type="button" class="upload_button button" onclick="return ajaxImageFileUploadToGallery($('#title').val(), '{$upload_id}', '{$VH->site_url("ajax/files_upload/$upload_id")}', '{$after_upload_url}', '{$upload_to}', '{$error_file_choose}');" value="{$LANG_BUTTON_UPLOAD_IMAGE}">
						</div>
					</div>
				</div>
				<img id="loading" src="{$public_path}images/ajax-loader.gif" style="display: none;">