{assign var="listing_id" value=$attrs.listing_id}
{assign var="files_limit" value=$attrs.files_limit}

				<script language="JavaScript" type="text/javascript">
					function appendRowToTable(data) {ldelim}
						var str = "<tr><td><input type='checkbox' name='cb_" + data.file_id + "' value='" + data.file_id + "'>" + "<" + "/td><td><a href='{$VH->site_url("admin/listings/files/edit/$listing_id/")}" + data.file_id + "'><img src='{$public_path}images/file_types/" + data.file_format + ".png'> " + data.file_title + " (" + data.file_size + ")" + "<" + "/a>" + "<" + "/td><td>" + data.file_format + "<" + "/td><td>" + data.creation_date + "<" + "/td><td><a href='{$VH->site_url("admin/listings/files/edit/$listing_id/")}" + data.file_id + "' title='{addslashes string=$LANG_EDIT_FILE_TITLE}'><img src='{$public_path}images/buttons/page_edit.png'></a>&nbsp;<a href='{$VH->site_url('admin/listings/files/delete/')}" + data.file_id + "' title='{addslashes string=$LANG_DELETE_FILE_TITLE}'><img src='{$public_path}images/buttons/page_delete.png'>" + "<" + "/a>&nbsp;" + "<" + "/td>" + "<" + "/tr>";
						$(str).appendTo("#upload_to_this_block");
						
						var counter = parseInt($("#files_counter").html())+1;
						$("#files_counter").html(counter);
						if (counter < {$files_limit})
							return true;
						else
							return false;
					{rdelim}
				</script>

				<div id="upload_wrapper">
					<label class="block_title">{$LANG_UPLOAD_FILE}</label>
					<div class="admin_option">
						<div class="admin_option_name">	
							{$LANG_NEW_FILE_TITLE} <i>(255 {$LANG_SYMBOLS_MAX})</i>
							{translate_content table='files' field='title' row_id='new'}
						</div>
						<input type="text" id="title" size="60" name="title" />

						<div class="px10"></div>

						<div class="admin_option_name">
							{$title}<span class="red_asterisk">*</span>
						</div>
						<div class="admin_option_description">
							{$LANG_MAX_IMAGE_SIZE} {$max_upload_filesize}. {$LANG_SUPPORTED_FORMAT}: {$VH->str_replace('|', ', ', $allowed_types)}
						</div>
						<div id="img_wrapper">
							<input id="{$upload_id}_browse" type="file" size="45" name="{$upload_id}_browse"><br>
							<input type="button" class="upload_button button" onclick="return ajaxFileUploadToStorage($('#title').val(), '{$public_path}images/file_types/', '{$upload_id}', '{$VH->site_url("ajax/files_upload/$upload_id")}', '{$after_upload_url}', '{$upload_to}', '{$error_file_choose}');" value="{$LANG_BUTTON_UPLOAD_FILE}">
						</div>
					</div>
				</div>
				<img id="loading" src="{$public_path}images/ajax-loader.gif" style="display: none;">