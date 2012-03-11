{include file="backend/admin_header.tpl"}
{assign var="level_id" value=$level->id}

<script language="JavaScript" type="text/javascript">
$(document).ready(function() {ldelim}
	$("#eternal_active_period").click( function() {ldelim}
		if ($('#eternal_active_period').is(':checked')) {ldelim}
        	$('#active_years').attr('disabled', true);
        	$('#active_months').attr('disabled', true);
        	$('#active_days').attr('disabled', true);
	    {rdelim} else {ldelim}
	        $('#active_years').removeAttr('disabled');
	        $('#active_months').removeAttr('disabled');
	        $('#active_days').removeAttr('disabled');
	    {rdelim}
	{rdelim});
	$("input[name='description_mode']").click( function() {ldelim}
		if ($("input[name='description_mode']:checked").val() == 'enabled') {ldelim}
        	$('#description_length').removeAttr('disabled');
	    {rdelim} else {ldelim}
	        $('#description_length').attr('disabled', true);
	    {rdelim}
	{rdelim});
	$("#logo_enabled").click( function() {ldelim}
		if ($('#logo_enabled').is(':checked')) {ldelim}
        	$('#logo_width').removeAttr('disabled');
        	$('#logo_height').removeAttr('disabled');
	    {rdelim} else {ldelim}
	        $('#logo_width').attr('disabled', true);
	        $('#logo_height').attr('disabled', true);
	    {rdelim}
	{rdelim});
	$("#maps_enabled").click( function() {ldelim}
		if ($('#maps_enabled').is(':checked')) {ldelim}
        	$('#maps_width').removeAttr('disabled');
        	$('#maps_height').removeAttr('disabled');
	    {rdelim} else {ldelim}
	        $('#maps_width').attr('disabled', true);
	        $('#maps_height').attr('disabled', true);
	    {rdelim}
	{rdelim});
	$("input[name='reviews_mode']").click( function() {ldelim}
		if ($("input[name='reviews_mode']:checked").val() == 'disabled') {ldelim}
        	$('#reviews_richtext_enabled').attr('disabled', true);
	    {rdelim} else {ldelim}
	    	$('#reviews_richtext_enabled').removeAttr('disabled');
	    {rdelim}
	{rdelim});
{rdelim});
</script>

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{if $level_id != 'new'}{$LANG_EDIT_LEVEL_1} "{$level->name}" {$LANG_EDIT_LEVEL_2} "{$type->name}" {else}{$LANG_CREATE_LEVEL} "{$type->name}"{/if}</h3>

                     {if $level_id != 'new'}
                     <div class="admin_top_menu_cell">
	                     <a href="{$VH->site_url("admin/levels/create/type_id/$type_id")}" title="{$LANG_CREATE_LEVEL_OPTION}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
	                     <a href="{$VH->site_url("admin/levels/create/type_id/$type_id")}">{$LANG_CREATE_LEVEL_OPTION}</a>&nbsp;&nbsp;&nbsp;
					 </div>
					 <div class="admin_top_menu_cell">
	                     <a href="{$VH->site_url("admin/levels/delete/$level_id")}" title="{$LANG_DELETE_LEVEL_OPTION}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
	                     <a href="{$VH->site_url("admin/levels/delete/$level_id")}">{$LANG_DELETE_LEVEL_OPTION}</a>&nbsp;&nbsp;&nbsp;
	                 </div>
	                 <div class="admin_top_menu_cell">
	                     <a href="{$VH->site_url("admin/fields/groups/manage_fields/$custom_group_id")}" title="{$LANG_CONFIGURE_FIELDS_OPTION}"><img src="{$public_path}/images/buttons/page_green.png"></a>
	                     <a href="{$VH->site_url("admin/fields/groups/manage_fields/$custom_group_id")}">{$LANG_CONFIGURE_FIELDS_OPTION}</a>&nbsp;&nbsp;&nbsp;
					 </div>
                     <div class="clear_float"></div>
					 <div class="px10"></div>
                     {/if}

                     <form action="" method="post">
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_NAME}
                          	{translate_content table='levels' field='name' row_id=$level_id}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_LEVEL_NAME_DESCR}
                          </div>
                          <input type=text name="name" value="{$level->name}" size="40" class="admin_option_input">
                     </div>
                     
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_ACTIVE_PERIOD}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_LEVEL_ACTIVE_PERIOD_DESCR}
                          </div>
                          <select name="active_years" id="active_years" class="no_min_width" {if $level->eternal_active_period}disabled="disabled"{/if}>
                          	<option value="0" {if $level->active_years == 0}selected{/if}>0</option>
                          	<option value="1" {if $level->active_years == 1}selected{/if}>1</option>
                          	<option value="2" {if $level->active_years == 2}selected{/if}>2</option>
                          	<option value="3" {if $level->active_years == 3}selected{/if}>3</option>
                          	<option value="4" {if $level->active_years == 4}selected{/if}>4</option>
                          	<option value="5" {if $level->active_years == 5}selected{/if}>5</option>
                          	<option value="6" {if $level->active_years == 6}selected{/if}>6</option>
                          	<option value="7" {if $level->active_years == 7}selected{/if}>7</option>
                          	<option value="8" {if $level->active_years == 8}selected{/if}>8</option>
                          	<option value="9" {if $level->active_years == 9}selected{/if}>9</option>
                          	<option value="10" {if $level->active_years == 10}selected{/if}>10</option>
                          </select> {$LANG_YEARS}
                          &nbsp;&nbsp;
                          <select name="active_months" id="active_months" class="no_min_width" {if $level->eternal_active_period}disabled="disabled"{/if}>
                          	<option value="0" {if $level->active_months == 0}selected{/if}>0</option>
                          	<option value="1" {if $level->active_months == 1}selected{/if}>1</option>
                          	<option value="2" {if $level->active_months == 2}selected{/if}>2</option>
                          	<option value="3" {if $level->active_months == 3}selected{/if}>3</option>
                          	<option value="4" {if $level->active_months == 4}selected{/if}>4</option>
                          	<option value="5" {if $level->active_months == 5}selected{/if}>5</option>
                          	<option value="6" {if $level->active_months == 6}selected{/if}>6</option>
                          	<option value="7" {if $level->active_months == 7}selected{/if}>7</option>
                          	<option value="8" {if $level->active_months == 8}selected{/if}>8</option>
                          	<option value="9" {if $level->active_months == 9}selected{/if}>9</option>
                          	<option value="10" {if $level->active_months == 10}selected{/if}>10</option>
                          	<option value="11" {if $level->active_months == 11}selected{/if}>11</option>
                          	<option value="12" {if $level->active_months == 12}selected{/if}>12</option>
                          </select> {$LANG_MONTHS}
                          &nbsp;&nbsp;
                          <select name="active_days" id="active_days" class="no_min_width" {if $level->eternal_active_period}disabled="disabled"{/if}>
                          	<option value="0" {if $level->active_days == 0}selected{/if}>0</option>
                          	<option value="1" {if $level->active_days == 1}selected{/if}>1</option>
                          	<option value="2" {if $level->active_days == 2}selected{/if}>2</option>
                          	<option value="3" {if $level->active_days == 3}selected{/if}>3</option>
                          	<option value="4" {if $level->active_days == 4}selected{/if}>4</option>
                          	<option value="5" {if $level->active_days == 5}selected{/if}>5</option>
                          	<option value="6" {if $level->active_days == 6}selected{/if}>6</option>
                          	<option value="7" {if $level->active_days == 7}selected{/if}>7</option>
                          	<option value="8" {if $level->active_days == 8}selected{/if}>8</option>
                          	<option value="9" {if $level->active_days == 9}selected{/if}>9</option>
                          	<option value="10" {if $level->active_days == 10}selected{/if}>10</option>
                          	<option value="11" {if $level->active_days == 11}selected{/if}>11</option>
                          	<option value="12" {if $level->active_days == 12}selected{/if}>12</option>
                          	<option value="13" {if $level->active_days == 13}selected{/if}>13</option>
                          	<option value="14" {if $level->active_days == 14}selected{/if}>14</option>
                          	<option value="15" {if $level->active_days == 15}selected{/if}>15</option>
                          	<option value="16" {if $level->active_days == 16}selected{/if}>16</option>
                          	<option value="17" {if $level->active_days == 17}selected{/if}>17</option>
                          	<option value="18" {if $level->active_days == 18}selected{/if}>18</option>
                          	<option value="19" {if $level->active_days == 19}selected{/if}>19</option>
                          	<option value="20" {if $level->active_days == 20}selected{/if}>20</option>
                          	<option value="21" {if $level->active_days == 21}selected{/if}>21</option>
                          	<option value="22" {if $level->active_days == 22}selected{/if}>22</option>
                          	<option value="23" {if $level->active_days == 23}selected{/if}>23</option>
                          	<option value="24" {if $level->active_days == 24}selected{/if}>24</option>
                          	<option value="25" {if $level->active_days == 25}selected{/if}>25</option>
                          	<option value="26" {if $level->active_days == 26}selected{/if}>26</option>
                          	<option value="27" {if $level->active_days == 27}selected{/if}>27</option>
                          	<option value="28" {if $level->active_days == 28}selected{/if}>28</option>
                          	<option value="29" {if $level->active_days == 29}selected{/if}>29</option>
                          	<option value="30" {if $level->active_days == 30}selected{/if}>30</option>
                          </select> {$LANG_DAYS}
                          <div style="padding-top:4px;">
                          	<label><input type="checkbox" value="1" id="eternal_active_period" name="eternal_active_period" {if $level->eternal_active_period}checked{/if}> {$LANG_ETERNAL}</label>
                          	<label><input type="checkbox" value="1" name="allow_to_edit_active_period" {if $level->allow_to_edit_active_period}checked{/if}> {$LANG_ALLOW_TO_EDIT_ACTIVE_PERIOD}</label>
                          </div>
                     </div>
                     
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_FEATURED}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_LEVEL_FEATURED_DESCR}
                          </div>
                          <input type="checkbox" name="featured" {if $level->featured == 1} checked {/if} />&nbsp;{$LANG_ENABLED}
                     </div>
                     
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_TITLE_ENABLED}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_LEVEL_TITLE_ENABLED_DESCR}
                          </div>
                          <input type="checkbox" name="title_enabled" {if $level->title_enabled == 1} checked {/if} />&nbsp;{$LANG_ENABLED}
                          
                          <div class="px5"></div>
                          
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_SEO_TITLE_ENABLED}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_LEVEL_SEO_TITLE_ENABLED_DESCR}
                          </div>
                          <input type="checkbox" name="seo_title_enabled" {if $level->seo_title_enabled == 1} checked {/if} />&nbsp;{$LANG_ENABLED}
                          
                          <div class="px5"></div>
                          
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_META_ENABLED}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_LEVEL_META_ENABLED_DESCR}
                          </div>
                          <input type="checkbox" name="meta_enabled" {if $level->meta_enabled == 1} checked {/if} />&nbsp;{$LANG_ENABLED}
                     </div>
                     
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_LISTING_DESCRIPTION}
                          </div>
                          <label><input type="radio" name="description_mode" value="enabled" {if $level->description_mode == 'enabled'} checked {/if} /> {$LANG_ENABLED}</label>
                          <label><input type="radio" name="description_mode" value="richtext" {if $level->description_mode == 'richtext'} checked {/if} /> {$LANG_LEVEL_LISTING_DESCRIPTION_RICHTEXT}</label>
                          <label><input type="radio" name="description_mode" value="disabled" {if $level->description_mode == 'disabled'} checked {/if} /> {$LANG_DISABLED}</label>
                          
                          <div class="px5"></div>
                          
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_LISTING_DESCRIPTION_LENGTH}
                          </div>
                          <input type="input" name="description_length" id="description_length" value="{$level->description_length}" size="3" {if $level->description_mode != 'enabled'}disabled{/if} />
                     </div>
                     
                     {if $type->categories_type != 'disabled'}
	                     <div class="admin_option">
	                          <div class="admin_option_name">
	                          	{$LANG_LEVEL_CATEGORIES_NUMBER}
	                          </div>
	                          <div class="admin_option_description">
	                          	{$LANG_LEVEL_CATEGORIES_NUMBER_DESCR}
	                          </div>
	                          <input type=text name="categories_number" value="{$level->categories_number}" size="2" class="admin_option_input">
	                     </div>
					 {/if}
                     
                     {if $type->locations_enabled}
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_LOCATIONS_NUMBER}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_LEVEL_LOCATIONS_NUMBER_DESCR}
                          </div>
                          <input type=text name="locations_number" value="{$level->locations_number}" size="2" class="admin_option_input">
                     </div>
                     {/if}
                     
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_TYPE_MODERATION}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_TYPE_MODERATION_DESCR}
                          </div>
                          <input type="checkbox" name="preapproved_mode" {if $level->preapproved_mode==1} checked {/if} />&nbsp;{$LANG_ENABLED}
                     </div>

                     <div class="admin_option">
                         <div class="admin_option_name">
                         	{$LANG_LEVEL_ENABLE_LOGO}
                         </div>
                         <div class="admin_option_description">
                         	{$LANG_LEVEL_ENABLE_LOGO_DESCR}
                         </div>
                         <input type="checkbox" name="logo_enabled" id="logo_enabled" {if $level->logo_enabled==1} checked {/if} />&nbsp;{$LANG_ENABLED}
                         <div class="px10"></div>
                         <div class="admin_option_name">
                         	{$LANG_LEVEL_LOGO_SIZE_1} <i>(308*400 {$LANG_LEVEL_LOGO_SIZE_2})</i>
                         </div>
                         {$LANG_WIDTH}, {$LANG_PX}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$LANG_HEIGHT}, {$LANG_PX}<br>
                         <input type=text name="logo_width" id="logo_width" value="{$level->explodeSize('logo_size', 0)}" size="4" class="admin_option_input" {if $level->logo_enabled == 0}disabled{/if}>&nbsp;&nbsp;&nbsp;&nbsp;
                         <input type=text name="logo_height" id="logo_height" value="{$level->explodeSize('logo_size', 1)}" size="4" class="admin_option_input" {if $level->logo_enabled == 0}disabled{/if}>
                     </div>
                     <div class="admin_option">
                         <div class="admin_option_name">
                         	{$LANG_LEVEL_IMAGES_COUNT}
                         </div>
                         <div class="admin_option_description">
                         	{$LANG_LEVEL_IMAGES_COUNT_DESCR}
                         </div>
                         <input type=text name="images_count" value="{$level->images_count}" size="1" class="admin_option_input">
                         <div class="px10"></div>
                         <div class="admin_option_name">
                         	{$LANG_LEVEL_IMAGES_SIZE}
                         </div>
                         {$LANG_WIDTH}, {$LANG_PX}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$LANG_HEIGHT}, {$LANG_PX}<br>
                         <input type=text name="images_width" value="{$level->explodeSize('images_size', 0)}" size="4" class="admin_option_input">&nbsp;&nbsp;&nbsp;&nbsp;
                         <input type=text name="images_height" value="{$level->explodeSize('images_size', 1)}" size="4" class="admin_option_input">
                         <br />
                     	 <br />
                         <div class="admin_option_name">
                         	{$LANG_LEVEL_THMBS_SIZE}
                          </div>
                         {$LANG_WIDTH}, {$LANG_PX}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$LANG_HEIGHT}, {$LANG_PX}<br>
                         <input type=text name="images_thumbnail_width" value="{$level->explodeSize('images_thumbnail_size', 0)}" size="4" class="admin_option_input">&nbsp;&nbsp;&nbsp;&nbsp;
                         <input type=text name="images_thumbnail_height" value="{$level->explodeSize('images_thumbnail_size', 1)}" size="4" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_VIDEOS_COUNT}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_LEVEL_VIDEOS_COUNT_DESCR}
                          </div>
                          <input type=text name="video_count" value="{$level->video_count}" size="1" class="admin_option_input">
                          <div class="px10"></div>
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_VIDEO_SIZE}
                          </div>
                         {$LANG_WIDTH}, {$LANG_PX}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$LANG_HEIGHT}, {$LANG_PX}<br>
                         <input type=text name="video_width" value="{$level->explodeSize('video_size', 0)}" size="4" class="admin_option_input">&nbsp;&nbsp;&nbsp;&nbsp;
                         <input type=text name="video_height" value="{$level->explodeSize('video_size', 1)}" size="4" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_FILES_COUNT}
                          </div>
                          <input type=text name="files_count" value="{$level->files_count}" size="1" class="admin_option_input">
                     </div>
                     
                     {if $type->locations_enabled}
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_MAPS}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_LEVEL_MAPS_DESCR}
                          </div>
                          <input type="checkbox" name="maps_enabled" id="maps_enabled" {if $level->maps_enabled==1} checked {/if}>&nbsp;{$LANG_ENABLED}
                          <div class="px10"></div>
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_MAPS_SIZE}
                          </div>
                         {$LANG_WIDTH}, {$LANG_PX}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$LANG_HEIGHT}, {$LANG_PX}<br>
                         <input type=text name="maps_width" id="maps_width" value="{$level->explodeSize('maps_size', 0)}" size="4" class="admin_option_input" {if $level->maps_enabled == 0}disabled{/if}>&nbsp;&nbsp;&nbsp;&nbsp;
                         <input type=text name="maps_height" id="maps_height" value="{$level->explodeSize('maps_size', 1)}" size="4" class="admin_option_input" {if $level->maps_enabled == 0}disabled{/if}>
                     </div>
                     {/if}

                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_OPTIONS}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_LEVEL_OPTIONS_DESC}
                          </div>
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_PRINT_OPTION}
                          </div>
                          <input type="checkbox" name="option_print"  {if $level->option_print==1} checked {/if}>&nbsp;{$LANG_ENABLED}
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_PDF_OPTION}
                          </div>
                          <input type="checkbox" name="option_pdf"  {if $level->option_pdf==1} checked {/if}>&nbsp;{$LANG_ENABLED}
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_QUICKLIST_OPTION}
                          </div>
                          <input type="checkbox" name="option_quick_list" {if $level->option_quick_list==1} checked {/if}>&nbsp;{$LANG_ENABLED}
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_EMAILFRIEND_OPTION}
                          </div>
                          <input type="checkbox" name="option_email_friend" {if $level->option_email_friend==1} checked {/if}>&nbsp;{$LANG_ENABLED}
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_EMAILOWNER_OPTION}
                          </div>
                          <input type="checkbox" name="option_email_owner" {if $level->option_email_owner==1} checked {/if}>&nbsp;{$LANG_ENABLED}
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_REPORT_OPTION}
                          </div>
                          <input type="checkbox" name="option_report" {if $level->option_report==1} checked {/if}>&nbsp;{$LANG_ENABLED}
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_LEVEL_SOCIAL}
                          </div>
                          <input type="checkbox" name="social_bookmarks_enabled" {if $level->social_bookmarks_enabled==1} checked {/if}>&nbsp;{$LANG_ENABLED}
                     </div>
                     <div class="admin_option">
                     	  <div class="admin_option_name">
                     	  	{$LANG_RATINGS_ENABLED}
                          </div>
                          <label><input type="checkbox" name="ratings_enabled" {if $level->ratings_enabled==1} checked {/if}>&nbsp;{$LANG_ENABLED}</label>
                          <div class="px10"></div>
                          <div class="admin_option_name">
                          	{$LANG_REVIEWS_MODE}
                          </div>
                          <label><input type="radio" name="reviews_mode" value="disabled" {if $level->reviews_mode=='disabled'} checked {/if}>&nbsp;{$LANG_DISABLED}</label>
                          <label><input type="radio" name="reviews_mode" value="reviews" {if $level->reviews_mode=='reviews'} checked {/if}>&nbsp;{$LANG_SINGLE_REVIEWS}</label>
                          <label><input type="radio" name="reviews_mode" value="comments" {if $level->reviews_mode=='comments'} checked {/if}>&nbsp;{$LANG_COMMENTS_TREE}</label>
                          <div class="px10"></div>
                          <div class="admin_option_name">
                     	  	{$LANG_REVIEWS_RICHTEXT_ENABLED}
                          </div>
                          <label><input type="checkbox" name="reviews_richtext_enabled" id="reviews_richtext_enabled" {if $level->reviews_richtext_enabled==1}checked{/if} {if $level->reviews_mode=='disabled'}disabled{/if} />&nbsp;{$LANG_ENABLED}</label>
                     </div>
                     <input type=submit name="submit" value="{if $level->id != 'new'}{$LANG_BUTTON_SAVE_CHANGES}{else}{$LANG_BUTTON_CREATE_LEVEL}{/if}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}