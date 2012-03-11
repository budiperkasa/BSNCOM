{include file="backend/admin_header.tpl"}
{assign var="banner_id" value=$banner->id}
{assign var="banners_block_id" value=$banner->block->id}

{if $banner->block->allow_remote_banners}
<script language="javascript" type="text/javascript">
	function ajaxRemoteFileLoad() {ldelim}
		if ($("#remote_image_url").val()) {ldelim}
			$("#remote_loading").show();
			$("#imgs_wrapper").hide();
			checkRemoteImage();
		{rdelim}
	{rdelim}

	function checkRemoteImage() {ldelim}
		if ($("#remote_image_url").val()) {ldelim}
			$.post('{$VH->site_url("ajax/banners/get_image_by_url/$banners_block_id")}', {ldelim}image_url:$("#remote_image_url").val(){rdelim}, function(data) {ldelim}
				if (data.error_msg == '') {ldelim}
					if (data.flash) {ldelim}
						$("#remote_image_wrapper").hide();
						$("#remote_flash_wrapper").show();
						swfobject.embedSWF(data.file_name, "flash_wrapper", "{$banner->block->explodeSize('block_size', 0)}", "{$banner->block->explodeSize('block_size', 1)}", "9.0.0");
						$("#is_loaded_flash").val(1);
					{rdelim} else {ldelim}
						$("#remote_image_wrapper").show();
						$("#remote_flash_wrapper").hide();
						$("#remote_image").attr('src', data.file_name);
						$("#is_loaded_flash").val(0);
					{rdelim}
					$("#use_remote_image").attr('checked', true);
				{rdelim} else {ldelim}
					alert(data.error_msg);
					$("#remote_image_wrapper").hide();
					$("#remote_flash_wrapper").hide();
					$("#remote_image").attr('src', '');
					$("#is_loaded_flash").val(0);
					$("#remote_image_url").val('');
					$("#use_remote_image").attr('checked', false);
				{rdelim}
				$("#imgs_wrapper").show();
				$("#remote_loading").hide();
			{rdelim}, 'json');
		{rdelim}
	{rdelim}

	$(document).ready(function() {ldelim}
		checkRemoteImage();
	{rdelim});
</script>
{/if}

                <div class="content">
                	{$VH->validation_errors()}
                    <h3>{if $banner_id != 'new'}{$LANG_BANNERS_EDIT_BANNER_TITLE}{else}{$LANG_BANNER_CREATE_TITLE}{/if}</h3>
                    {if $banner_id == 'new'}<h4>{$LANG_BANNERS_ADD_STEP2}</h4>{/if}

                    {if $banner_id !='new' }
                    <div class="admin_top_menu_cell">
	                   <a href="{$VH->site_url("admin/banners/create")}" title="{$LANG_CREATE_BANNER}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
	                   <a href="{$VH->site_url("admin/banners/create")}">{$LANG_CREATE_BANNER}</a>
					</div>
	                <div class="admin_top_menu_cell">
	                    <a href="{$VH->site_url("admin/banners/edit/$banner_id")}" title="{$LANG_EDIT_BANNER}"><img src="{$public_path}/images/buttons/page_edit.png" /></a>
	                    <a href="{$VH->site_url("admin/banners/edit/$banner_id")}">{$LANG_EDIT_BANNER}</a>
	                </div>
	                <div class="admin_top_menu_cell">
	                    <a href="{$VH->site_url("admin/banners/delete/$banner_id")}" title="{$LANG_DELETE_BANNER}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
	                    <a href="{$VH->site_url("admin/banners/delete/$banner_id")}">{$LANG_DELETE_BANNER}</a>
	                </div>
					<div class="clear_float"></div>
					<div class="px10"></div>
                    {/if}

                     <form action="" method="post">
                     <input type="hidden" name="block_id" value="{$banner->block->id}">
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_BANNER_LINK_URL}<span class="red_asterisk">*</span>
                          </div>
                          <input type=text name="url" value="{$banner->url}" size="85" class="admin_option_input">
                     </div>
                     
                     {$banner_upload_block->setUploadBlock('files_upload/banner_image_upload_block.tpl')}

                     {if $banner->block->allow_remote_banners}
                     <div class="admin_option">
						<div class="admin_option_name">
							{$LANG_BANNER_REMOTE_IMAGE}
						</div>
						<div class="admin_option_description">
							{$LANG_MAX_IMAGE_SIZE} {$banner->block->explodeSize('block_size', 0)}*{$banner->block->explodeSize('block_size', 1)}px, {$LANG_MAX_FILE_SIZE} {$CI->config->item('max_upload_filesize')}. {$LANG_SUPPORTED_FORMAT}: {$VH->str_replace('|', ', ', $allowed_types)}
						</div>
						<div id="imgs_wrapper">
							<div id="remote_image_wrapper" {if $banner->is_loaded_flash || !$banner->remote_image_url}style="display: none"{/if} >
						    	<img id="remote_image" src="{$banner->remote_image_url}"/>
							</div>
							<div id="remote_flash_wrapper" {if !$banner->is_loaded_flash || !$banner->remote_image_url}style="display: none"{/if}>
								<div id="flash_wrapper">
									<script language="javascript" type="text/javascript">
							    		swfobject.embedSWF("{$banner->remote_image_url}", "flash_wrapper", "{$banner->block->explodeSize('block_size', 0)}", "{$banner->block->explodeSize('block_size', 1)}", "9.0.0");
							    	</script>
							    </div>
							</div>
							<div class="px5"></div>
							<input type="text" name="remote_image_url" id="remote_image_url" size="85" value="{$banner->remote_image_url}">
							<div class="px5"></div>
							<input type="button" class="upload_button button" onclick="return ajaxRemoteFileLoad();" value="{$LANG_BUTTON_LOAD_IMAGE}">
							<div class="px5"></div>
							<label><input type="checkbox" name="use_remote_image" id="use_remote_image" value="1" {if $banner->use_remote_image}checked{/if} /> {$LANG_USE_REMOTE_BANNER}</label>
						</div>
						<img id="remote_loading" src="{$public_path}images/ajax-loader.gif" style="display: none;">
						<input type="hidden" name="is_loaded_flash" id="is_loaded_flash" {if $banner->is_loaded_flash}value=1{/if} />
					 </div>
					 {/if}

					{render_frontend_block
						block_type='locations'
						block_template='backend/blocks/admin_locations_for_banners.tpl'
						is_only_labeled=true
						is_counter=false
						max_depth='max'
						banner=$banner
						no_cache=true
					}

					{render_frontend_block
						block_type='categories'
						block_template='backend/blocks/admin_categories_for_banners.tpl'
						is_counter=false
						max_depth='max'
						banner=$banner
						no_cache=true
					}

					<input class="button save_button" type=submit name="submit" value="{if $banner_id != 'new'}{$LANG_BUTTON_SAVE_CHANGES}{else}{$LANG_BUTTON_ADD_BANNER}{/if}">
					</form>
				</div>

{include file="backend/admin_footer.tpl"}