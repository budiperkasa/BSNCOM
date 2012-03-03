{include file="backend/admin_header.tpl"}
{assign var="location_id" value=$location->id}

<script language="javascript" type="text/javascript">
{if $parent_location}
var parent_locations = ', {$parent_location->getChainAsString()}';
{else}
var parent_locations = '';
{/if}

var use_districts = {$system_settings.geocoded_locations_mode_districts};
var use_provinces = {$system_settings.geocoded_locations_mode_provinces};

$(document).ready(function() {ldelim}
	var response = (function(val) {ldelim}
		ajax_loader_hide();
		this.res = val;
		if (val) {ldelim}
			var suggestions_string = '<div style="padding-bottom: 10px"><h4>{$LANG_GEO_NAME_DESCR}<'+'/h4>';
			for (var i=0; i<this.res.length; i++) {ldelim}
				if ($("#selected_suggestion").val()==this.res[i].label || i==0)
					var str_checked = 'checked';
				else
					var str_checked = '';
				suggestions_string = suggestions_string + '<label><input name="geocoded_name" type="radio" ' + str_checked + ' value="' + this.res[i].label + '" /> ' + this.res[i].label + '<'+'/label>';
			{rdelim}
			suggestions_string = suggestions_string + '<'+'/div>';
			$("#geocoded_suggestions").html(suggestions_string).show();
		{rdelim}
	{rdelim});

	$("input[name='geocoded_name']").live('change', function() {ldelim}
		if ($("input[name='geocoded_name']:checked").val())
			$("#selected_suggestion").val($("input[name='geocoded_name']:checked").val());
	{rdelim});

	$('#name').keyup(function() {ldelim}
		delay(function() {ldelim}
			ajax_loader_show('GeoCoding...');
			geocodeAddress($('#name').val() + parent_locations, '{$system_settings.default_language}', response, false, false);
		{rdelim}, 1500);
	{rdelim});

	ajax_loader_show('GeoCoding...');
	geocodeAddress($('#name').val() + parent_locations, '{$system_settings.default_language}', response, false, false);
{rdelim});
</script>

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{if $location_id == 'new'}{$LANG_CREATE_LOCATION}{else}{$LANG_EDIT_LOCATION}{/if}</h3>
                     
                     {if $location_id != 'new'}
                     <a href="{$VH->site_url("admin/locations/create")}" title="{$LANG_NEW_LOCATION_OPTION}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
                     <a href="{$VH->site_url("admin/locations/create")}">{$LANG_NEW_LOCATION_OPTION}</a>&nbsp;&nbsp;&nbsp;

                     <a href="{$VH->site_url("admin/locations/delete/$location_id")}" title="{$LANG_DELETE_LOCATION_OPTION}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
                     <a href="{$VH->site_url("admin/locations/delete/$location_id")}">{$LANG_DELETE_LOCATION_OPTION}</a>&nbsp;&nbsp;&nbsp;
                     <br />
                     <br />
                     {/if}

                     <form action="" method="post">
                     <div class="admin_option">
                     	<div style="float: left;">
                          <div class="admin_option_name">
                          	{$LANG_LOCATIONS_NAME}<span class="red_asterisk">*</span>
                          	{translate_content table='locations' field='name' row_id=$location_id}
                          </div>
                          <input type=text id="name" name="name" value="{$location->name}" size="45" class="admin_option_input">
                          &nbsp;&nbsp;<span class="seo_rewrite" title="{$LANG_WRITE_SEO_STYLE}"><img src="{$public_path}images/arrow_seo.gif"></span>&nbsp;&nbsp;
                        </div>
                        <div style="float: left;">
                          <div class="admin_option_name">
                          	{$LANG_LOCATIONS_SEO_NAME}<span class="red_asterisk">*</span>
                          </div>
                          <input type=text id="seo_name" name="seo_name" value="{$location->seo_name}" size="45" class="admin_option_input">&nbsp;&nbsp;
                        </div>
                        <div style="clear: both"></div>
                     </div>

                     <input type="hidden" id="selected_suggestion" value="{$location->geocoded_name}">
                     <div id="geocoded_suggestions" style="display: none;"></div>

                     <input class="button save_button" type=submit name="submit" value="{if $category_id != 'new'}{$LANG_BUTTON_SAVE_CHANGES}{else}{$LANG_BUTTON_CREATE_LOCATION}{/if}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}