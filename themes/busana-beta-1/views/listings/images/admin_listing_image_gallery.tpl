{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
				// Command variable, needs for delete listings button
				var action_cmd;

                function submit_images_form()
                {ldelim}
                	$("#images_form").attr("action", '{$VH->site_url("admin/listings/images/")}' + action_cmd + '/');
                	return true;
                {rdelim}
                </script>

                <div class="content">
                    <h3>{$LANG_MANAGE_IMAGES} "{$listing->title()}"</h3>

                    {include file="listings/admin_listing_options_menu.tpl"}

                    <div id="images_gallery">
                    	<h4>{$LANG_MANAGE_IMAGES_STORAGE_1}: <span id="images_counter">{$images|@count}</span> ({$listing->level->images_count} {$LANG_MANAGE_IMAGES_STORAGE_2})</h4>
                    	
                    	<form id="images_form" action="" method="post">
                    	<table id="upload_to_this_block" class="standardTable" border="0" cellpadding="2" cellspacing="2">
                        <tr>
                          <th width="1"><input type="checkbox"></th>
                          <th>{$LANG_IMAGE_TH}</th>
                          <th>{$LANG_TITLE_TH}</th>
                          <th>{$LANG_UPLOAD_DATE_TH}</th>
                          <th>{$LANG_OPTIONS_TH}</th>
                        </tr>
                    	{foreach from=$images item=image}
                    	{assign var="image_id" value=$image->id}
                    	<tr>
                    	  <td>
                    	  	<input type="checkbox" name="cb_{$image->id}" value="{$image->id}">
                    	  </td>
                    	  <td>
                    	  	<a href="{$VH->site_url("admin/listings/images/edit/$listing_id/$image_id")}"><img src='{$users_content}users_images/thmbs/{$image->file}'></a>
                    	  </td>
                    	  <td>
                    	  	<a href="{$VH->site_url("admin/listings/images/edit/$listing_id/$image_id")}">{$image->title}</a>
                    	  </td>
                    	  <td>
                    	  	{$image->creation_date|date_format:"%D %T"}
                    	  </td>
                    	  <td>
                    	  	<a href="{$VH->site_url("admin/listings/images/edit/$listing_id/$image_id")}" title="{$LANG_EDIT_FILE_TITLE}"><img src="{$public_path}images/buttons/page_edit.png"></a>&nbsp;
                    	  	<a href="{$VH->site_url("admin/listings/images/delete/$image_id")}" title="{$LANG_DELETE_FILE_TITLE}"><img src="{$public_path}images/buttons/page_delete.png"></a>&nbsp;
                    	  </td>
                    	</tr>
                    	{/foreach}
                    	</table>
                    	
                    	<input type="hidden" name="listing_id" value="{$listing_id}">
                    	{$LANG_WITH_SELECTED}:
	                    	<select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_images_form(); this.form.submit()">
	                    		<option value="">{$LANG_CHOOSE_ACTION}</option>
	                    		<option value="delete">{$LANG_BUTTON_DELETE_IMAGES}</option>
	                    	</select>
                    	</form>
                    </div>

                    {if $images|@count < $listing->level->images_count}
                    {$image_upload_block->setUploadBlock('files_upload/image_gallery_upload_block.tpl')}
                    {/if}
                </div>

{include file="backend/admin_footer.tpl"}