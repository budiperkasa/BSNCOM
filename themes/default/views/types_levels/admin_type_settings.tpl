{include file="backend/admin_header.tpl"}
{assign var="type_id" value=$type->id}

                <div class="content">
                	{$VH->validation_errors()}
             	
					<h3>{if $type_id != 'new'}{$LANG_EDIT_TYPE} "{$type_name}"{else}{$LANG_CREATE_TYPE}{/if}</h3>
					{if $type_id !='new' }
					{if !$system_settings.single_type_structure && $types|@count >= 1}
					<div class="admin_top_menu_cell">
	                     <a href="{$VH->site_url("admin/types/create")}" title="{$LANG_CREATE_TYPE_OPTION}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
	                     <a href="{$VH->site_url("admin/types/create")}">{$LANG_CREATE_TYPE_OPTION}</a>
					</div>
					{/if}
					<div class="admin_top_menu_cell">
						<a href="{$VH->site_url("admin/types/delete/$type_id")}" title="{$LANG_DELETE_TYPE_OPTION}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
						<a href="{$VH->site_url("admin/types/delete/$type_id")}">{$LANG_DELETE_TYPE_OPTION}</a>&nbsp;&nbsp;&nbsp;
					</div>
					<div class="admin_top_menu_cell">
						<a href="{$VH->site_url("admin/levels/type_id/$type_id")}" title="{$LANG_VIEW_LEVELS_OPTION}"><img src="{$public_path}/images/buttons/page_green.png"></a>
						<a href="{$VH->site_url("admin/levels/type_id/$type_id")}">{$LANG_VIEW_LEVELS_OPTION}</a>&nbsp;&nbsp;&nbsp;
					</div>
					<div class="clear_float"></div>
					<div class="px10"></div>
					{/if}
					
					{if $system_settings.single_type_structure}
                     <div>
                     	<b><i>{$LANG_SIGNLE_TYPE_NOTIFICATION}</i></b>
                     </div>
                     <div class="px10"></div>
                     {/if}

                     <form action="" method="post">
                     <input type=hidden name="id" value="{$type_id}">
                     <div class="admin_option">
						<div style="float: left;">
                          <div class="admin_option_name" >
                          	{$LANG_TYPE_NAME}<span class="red_asterisk">*</span>
                          	{translate_content table='types' field='name' row_id=$type_id}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_TYPE_NAME_DESCR}
                          </div>
                          <input type=text name="name" value="{$type->name}" size="40" class="admin_option_input">
                          &nbsp;&nbsp;<span class="seo_rewrite" title="{$LANG_WRITE_SEO_STYLE}"><img src="{$public_path}images/arrow_seo.gif"></span>&nbsp;&nbsp;
                     	</div>
                     	<div style="float: left;">
                          <div class="admin_option_name">
                          	{$LANG_TYPE_SEO_NAME}<span class="red_asterisk">*</span>
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_SEO_DESCR}
                          </div>
                          <input type=text name="seo_name" id="seo_name" value="{$type->seo_name}" size="40" class="admin_option_input">
                     	</div>
                     	<div style="clear: both"></div>
                     </div>

                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_TYPE_LOCATIONS}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_TYPE_LOCATIONS_DESCR}
                          </div>
                          <input type="checkbox" name="locations_enabled" {if $type->locations_enabled==1} checked {/if} />&nbsp;{$LANG_ENABLED}
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_TYPE_ZIP}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_TYPE_ZIP_DESCR}
                          </div>
                          <input type="checkbox" name="zip_enabled" {if $type->zip_enabled==1} checked {/if} />&nbsp;{$LANG_ENABLED}
                     </div>
                     {if !$system_settings.single_type_structure}
                     <label class="block_title">{$LANG_TYPE_SEARCH_SETTINGS}</label>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_TYPE_SEARCH_TYPE}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_TYPE_SEARCH_TYPE_DESCR}
                          </div>
                          <label><input type="radio" name="search_type" value="global" {if $type->search_type=='global'} checked {/if} />&nbsp;{$LANG_GLOABAL_SEARCH}</label>
                          <label><input type="radio" name="search_type" value="local" {if $type->search_type=='local'} checked {/if} />&nbsp;{$LANG_LOCAL_SEARCH}</label>
                          <label><input type="radio" name="search_type" value="disabled" {if $type->search_type=='disabled'} checked {/if} />&nbsp;{$LANG_DISABLED}</label>
                          
                          <div class="px10"></div>
                          
                          <div class="admin_option_name">
                          	{$LANG_TYPE_WHAT_SEARCH}
                          </div>
                          <label><input type="radio" name="what_search" value="1" {if $type->what_search} checked {/if} />&nbsp;{$LANG_ENABLED}</label>
                          <label><input type="radio" name="what_search" value="0" {if !$type->what_search} checked {/if} />&nbsp;{$LANG_DISABLED}</label>
                          
                          <div class="px10"></div>
                          
                          <div class="admin_option_name">
                          	{$LANG_TYPE_WHERE_SEARCH}
                          </div>
                          <label><input type="radio" name="where_search" value="1" {if $type->where_search} checked {/if} />&nbsp;{$LANG_ENABLED}</label>
                          <label><input type="radio" name="where_search" value="0" {if !$type->where_search} checked {/if} />&nbsp;{$LANG_DISABLED}</label>

                          <div class="px10"></div>
                          
                          <div class="admin_option_name">
                          	{$LANG_TYPE_CATEGORIES_SEARCH}
                          </div>
                          <label><input type="radio" name="categories_search" value="1" {if $type->categories_search} checked {/if} />&nbsp;{$LANG_ENABLED}</label>
                          <label><input type="radio" name="categories_search" value="0" {if !$type->categories_search} checked {/if} />&nbsp;{$LANG_DISABLED}</label>
                     </div>
                     <label class="block_title">{$LANG_TYPE_CATEGORIES_SETTINGS}</label>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_TYPE_CATEGORIES_TYPE}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_TYPE_CATEGORIES_TYPE_DESCR}
                          </div>
                          <label><input type="radio" name="categories_type" value="global" {if $type->categories_type=='global'} checked {/if} />&nbsp;{$LANG_GLOABAL_CATEGORIES}</label>
                          <label><input type="radio" name="categories_type" value="local" {if $type->categories_type=='local'} checked {/if} />&nbsp;{$LANG_LOCAL_CATEGORIES}</label>
                          <label><input type="radio" name="categories_type" value="disabled" {if $type->categories_type=='disabled'} checked {/if} />&nbsp;{$LANG_DISABLED}</label>
                     </div>
                     {/if}
                     <div class="admin_option">
                     	<div class="admin_option_name">
                          	{$LANG_META_TITLE}
                          	{translate_content table='types' field='meta_title' row_id=$type_id}
                        </div>
                        <div class="admin_option_description">
                        	{$LANG_META_TITLE_DESCR}
                        </div>
                        <input type=text name="meta_title" value="{$type->meta_title}" size="60" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                     	<div class="admin_option_name">
                          	{$LANG_META_DESCRIPTION}
                          	{translate_content table='types' field='meta_description' row_id=$type_id field_type='text'}
                        </div>
                        <div class="admin_option_description">
                        	{$LANG_META_TITLE_DESCR}
                        </div>
                        <textarea name="meta_description" cols="60" rows="5">{$type->meta_description}</textarea>
                     </div>

                     <input class="button save_button" type=submit name="submit" value="{if $type_id != 'new'}{$LANG_BUTTON_SAVE_CHANGES}{else}{$LANG_BUTTON_CREATE_TYPE}{/if}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}