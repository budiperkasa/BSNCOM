{assign var="banner_id" value=$banner->id}
<div class="banners_block" style="width:{$banner->block->explodeSize('block_size', 0)}px;">
	{if $banner->use_remote_image}
		{if !$banner->is_loaded_flash}
			<a href="{$banner->url}" target="_blank" onclick="jQuery.get('{$VH->site_url("ajax/banners/click_tracing/$banner_id")}')">
				<img id="img_{$banner->id}" src="{$banner->remote_image_url}" />
			</a>
		{else}
			<div class="fire_onclick" onmousedown="jQuery.get('{$VH->site_url("ajax/banners/click_tracing/$banner_id")}', function(data) {ldelim}location.href='{$banner->url}';{rdelim});">
				<div id="remote_image_wrapper_{$banner->id}">
					<script language="javascript" type="text/javascript">
						swfobject.embedSWF("{$banner->remote_image_url}", "remote_image_wrapper_{$banner->id}", "{$banner->block->explodeSize('block_size', 0)}", "{$banner->block->explodeSize('block_size', 1)}", "9.0.0", "", {ldelim}{rdelim}, {ldelim}wmode:'transparent', bgcolor: '#fff'{rdelim});
					</script>
				</div>
			</div>
		{/if}
	{else}
		{if !$banner->is_uploaded_flash}
			<a href="{$banner->url}" target="_blank" onclick="jQuery.get('{$VH->site_url("ajax/banners/click_tracing/$banner_id")}')">
				<img id="img_{$banner->id}" src="{$users_content}banners/{$banner->banner_file}" />
			</a>
		{else}
			<div class="fire_onclick" onmousedown="jQuery.get('{$VH->site_url("ajax/banners/click_tracing/$banner_id")}', function(data) {ldelim}location.href='{$banner->url}';{rdelim});">
				<div id="image_wrapper_{$banner->id}">
					<script language="javascript" type="text/javascript">
						swfobject.embedSWF("{$users_content}banners/{$banner->banner_file}", "image_wrapper_{$banner->id}", "{$banner->block->explodeSize('block_size', 0)}", "{$banner->block->explodeSize('block_size', 1)}", "9.0.0", "", {ldelim}{rdelim}, {ldelim}wmode:'transparent', bgcolor: '#fff'{rdelim});
					</script>
				</div>
			</div>
		{/if}
	{/if}
	{if $content_access_obj->isPermission('Create banners')}
	<div class="banners_advertise_link">
		<a href="{$VH->site_url('advertise')}#banners_advertise">{$LANG_TOP_MENU_ADS}</a>
	</div>
	{/if}
</div>