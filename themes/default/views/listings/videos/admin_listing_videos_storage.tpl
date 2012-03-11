{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
				// Command variable, needs for delete listings button
				var action_cmd;

                function submit_videos_form()
                {ldelim}
                	$("#videos_form").attr("action", '{$VH->site_url("admin/listings/videos/")}' + action_cmd + '/');
                	return true;
                {rdelim}
                </script>

                <div class="content">
                    <h3>{$LANG_MANAGE_VIDEOS} "{$listing->title()}"</h3>

                    {include file="listings/admin_listing_options_menu.tpl"}

                    <div id="files_storage">
                    	<h4>{$LANG_LISTING_VIDEOS_STORAGE_1}: <span id="videos_counter">{$videos|@count}</span> ({$listing->level->video_count} {$LANG_LISTING_VIDEOS_STORAGE_2})</h4>

                    	{if $videos|@count < $listing->level->video_count}
                    	{if $system_settings.youtube_key && $system_settings.youtube_username && $system_settings.youtube_password && $system_settings.youtube_product_name}
                    	<a href="{$VH->site_url("admin/listings/videos/upload/$listing_id")}" title="{$LANG_UPLOAD_VIDEO_OPTION}"><img src="{$public_path}/images/buttons/videos_add.png" /></a>
                    	<a href="{$VH->site_url("admin/listings/videos/upload/$listing_id")}">{$LANG_UPLOAD_VIDEO_OPTION}</a>&nbsp;&nbsp;&nbsp;
                    	{/if}

                    	<a href="{$VH->site_url("admin/listings/videos/attach/$listing_id")}" title="{$LANG_ATTACH_VIDEO_OPTION}"><img src="{$public_path}/images/buttons/videos_attach.png" /></a>
                    	<a href="{$VH->site_url("admin/listings/videos/attach/$listing_id")}">{$LANG_ATTACH_VIDEO_OPTION}</a>
                    	<div class="px10"></div>
                    	{/if}

                    	<form id="videos_form" action="" method="post">
                    	<table id="upload_to_this_block" class="standardTable" border="0" cellpadding="2" cellspacing="2">
                        <tr>
                          <th width="1"><input type="checkbox"></th>
                          <th width="200">{$LANG_STATUS_TH}</th>
                          <th>{$LANG_THUMBNAIL_TH}</th>
                          <th>{$LANG_VIDEO_TH}</th>
                          <th>{$LANG_UPLOAD_DATE_TH}</th>
                          <th>{$LANG_OPTIONS_TH}</th>
                        </tr>
                    	{foreach from=$videos item=video}
                    	{assign var="video_id" value=$video->id}
                    	<tr>
                    	  <td>
                    	  	<input type="checkbox" name="cb_{$video->id}" value="{$video->id}">
                    	  </td>
                    	  <td>
                    	  	{if $video->status == 'success'}{$LANG_VIDEO_STATUS_ACTIVE}{/if}
	                    	{if $video->status == 'processing'}{$LANG_VIDEO_STATUS_PROCESSING}{/if}
	                    	{if $video->status == 'restricted'}{$LANG_VIDEO_STATUS_RESTRICTED} ({$video->error_code}){/if}
	                    	{if $video->status == 'rejected'}{$LANG_VIDEO_STATUS_REJECTED} ({$video->error_code}){/if}
	                    	{if $video->status == 'failed'}{$LANG_VIDEO_STATUS_FAILED} ({$video->error_code}){/if}
	                    	{if $video->status == 'error'}{$LANG_VIDEO_ERROR} ({$video->error_code}){/if}
                    	  </td>
                    	  <td>
                    	  	<a href="{$VH->site_url("admin/listings/videos/edit/$listing_id/$video_id")}"><img src="http://img.youtube.com/vi/{$video->video_code}/2.jpg" /></a>
                    	  </td>
                    	  <td>
                    	  	<a href="{$VH->site_url("admin/listings/videos/edit/$listing_id/$video_id")}">{$video->title}</a>
                    	  </td>
                    	  <td>
                    	  	{$video->creation_date|date_format:"%D %T"}
                    	  </td>
                    	  <td>
                    	  	<a href="{$VH->site_url("admin/listings/videos/edit/$listing_id/$video_id")}" title="{$LANG_EDIT_VIDEO_TITLE}"><img src="{$public_path}images/buttons/page_edit.png"></a>&nbsp;
                    	  	<a href="{$VH->site_url("admin/listings/videos/delete/$video_id")}" title="{$LANG_BUTTON_VIDEO_DELETE}"><img src="{$public_path}images/buttons/page_delete.png"></a>&nbsp;
                    	  </td>
                    	</tr>
                    	{/foreach}
                    	</table>

                    	<input type="hidden" name="listing_id" value="{$listing_id}">
                    	{$LANG_WITH_SELECTED}:
	                    	<select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_videos_form(); this.form.submit()">
	                    		<option value="">{$LANG_CHOOSE_ACTION}</option>
	                    		<option value="delete">{$LANG_BUTTON_DELETE_VIDEOS}</option>
	                    	</select>
                    	</form>
                    </div>
                </div>

{include file="backend/admin_footer.tpl"}