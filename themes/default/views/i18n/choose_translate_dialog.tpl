	<script language="javascript" type="text/javascript">
		translate_window_url = "{$VH->site_url("admin/languages/translate_window/$table/$field/$row_id/$hash/$field_type/$virtual_id")}";
	</script>

	<div>
		<div id="lang_areas_panel" class="lang_areas_panel" style="display: block">
		{if $languages|@count > 0}
			{foreach from=$languages item=languages_item}
			{if $languages_item.active && $languages_item.code != $current_language}
			<div class="lang_area_panel_flag">
				<div style="margin-bottom: 5px;">
					<a href="javascript: void(0);" onClick="selectAreaTranslate(this); return false;" title="{$languages_item.db_code}"><img src="{$public_path}images/flags/{$languages_item.flag}"></a>
				</div>
				<div>
					<a href="javascript: void(0);" onClick="selectAreaTranslate(this); return false;" title="{$languages_item.db_code}">{$languages_item.name}</a>
				</div>
			</div>
			{/if}
			{/foreach}
			<div class="clear_float"></div>
		{/if}
		</div>
	</div>