{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
				// Command variable, needs for delete listings button
				var action_cmd;

                function submit_files_form()
                {ldelim}
                	$("#files_form").attr("action", '{$VH->site_url("admin/listings/files/")}' + action_cmd + '/');
                	return true;
                {rdelim}
                </script>

                <div class="content">
                    <h3>{$LANG_MANAGE_FILES} "{$listing->title()}"</h3>

                    {include file="listings/admin_listing_options_menu.tpl"}

                    <div id="files_storage">
                    	<h4>{$LANG_LISTING_FILES_STORAGE_1}: <span id="files_counter">{$files|@count}</span> ({$listing->level->files_count} {$LANG_LISTING_FILES_STORAGE_2})</h4>
                    	
                    	<form id="files_form" action="" method="post">
                    	<table id="upload_to_this_block" class="standardTable" border="0" cellpadding="2" cellspacing="2">
                        <tr>
                          <th width="1"><input type="checkbox"></th>
                          <th>{$LANG_FILES_SIZE_TH}</th>
                          <th>{$LANG_FILES_FORMAT_TH}</th>
                          <th>{$LANG_UPLOAD_DATE_TH}</th>
                          <th>{$LANG_OPTIONS_TH}</th>
                        </tr>
                    	{foreach from=$files item=file}
                    	{assign var="file_id" value=$file->id}
                    	<tr>
                    	  <td>
                    	  	<input type="checkbox" name="cb_{$file_id}" value="{$file_id}">
                    	  </td>
                    	  <td>
                    	  	<a href="{$VH->site_url("admin/listings/files/edit/$listing_id/$file_id")}"><img src='{$public_path}images/file_types/{$file->file_format}.png'> {$file->title} ({$file->file_size})</a>
                    	  </td>
                    	  <td>
                    	  	{$file->file_format}
                    	  </td>
                    	  <td>
                    	  	{$file->creation_date|date_format:"%D %T"}
                    	  </td>
                    	  <td>
                    	  	<a href="{$VH->site_url("admin/listings/files/edit/$listing_id/$file_id")}" title="{$LANG_EDIT_FILE_TITLE}"><img src="{$public_path}images/buttons/page_edit.png"></a>&nbsp;
                    	  	<a href="{$VH->site_url("admin/listings/files/delete/$file_id")}" title="{$LANG_DELETE_FILE_TITLE}"><img src="{$public_path}images/buttons/page_delete.png"></a>&nbsp;
                    	  </td>
                    	</tr>
                    	{/foreach}
                    	</table>

                    	<input type="hidden" name="listing_id" value="{$listing_id}">
                    	{$LANG_WITH_SELECTED}:
	                    	<select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_files_form(); this.form.submit()">
	                    		<option value="">{$LANG_CHOOSE_ACTION}</option>
	                    		<option value="delete">{$LANG_BUTTON_DELETE_FILES}</option>
	                    	</select>
                    	</form>
                    </div>

                    {if $files|@count < $listing->level->files_count}
                    <div class="px5"></div>
                    {$file_upload_block->setUploadBlock('files_upload/file_upload_block.tpl')}
                    {/if}
                </div>

{include file="backend/admin_footer.tpl"}