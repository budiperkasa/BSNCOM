{include file="backend/admin_header.tpl"}
{assign var="field_id" value=$field->id}

				<div class="content">
					{$VH->validation_errors()}
					<h3>{if $field_id != 'new'}{$LANG_EDIT_FIELD}{else}{$LANG_CREATE_FIELD}{/if}</h3>

					{if $field_id != 'new'}
						{if $field->configuration_page}
							<a href="{$VH->site_url("admin/fields/configure/$field_id")}" title="{$LANG_CONFIGURE_FIELD_OPTION}"><img src="{$public_path}/images/buttons/page_gear.png"></a>
							<a href="{$VH->site_url("admin/fields/configure/$field_id")}">{$LANG_CONFIGURE_FIELD_OPTION}</a>&nbsp;&nbsp;&nbsp;&nbsp;
						{/if}
						{if $field->search_configuration_page}
                         	<a href="{$VH->site_url("admin/fields/configure_search/$field_id")}" title="{$LANG_CONFIGURE_SEARCH_FIELD_OPTION}"><img src="{$public_path}images/buttons/page_find.png"></a>
                         	<a href="{$VH->site_url("admin/fields/configure_search/$field_id")}">{$LANG_CONFIGURE_SEARCH_FIELD_OPTION}</a>&nbsp;&nbsp;&nbsp;&nbsp;
                        {/if}
						<a href="{$VH->site_url("admin/fields/delete/$field_id")}"><img src="{$public_path}/images/buttons/page_delete.png"></a>
						<a href="{$VH->site_url("admin/fields/delete/$field_id")}">{$LANG_DELETE_FIELD_OPTION}</a>&nbsp;&nbsp;&nbsp;&nbsp;
						<br />
						<br />
					{/if}

					<form action="" method="post">
					<div class="admin_option">
						<div style="float: left;">
							<div class="admin_option_name" >
								{$LANG_FIELD_NAME}<span class="red_asterisk">*</span>
								{translate_content table='content_fields' field='name' row_id=$field_id}
							</div>
							<div class="admin_option_description">
								{$LANG_FIELD_NAME_DESCR}
							</div>
							<input type=text name="name" value="{$field->name}" size="40" class="admin_option_input">
							&nbsp;&nbsp;<span class="seo_rewrite" title="{$LANG_WRITE_SEO_STYLE}"><img src="{$public_path}images/arrow_seo.gif"></span>&nbsp;&nbsp;
						</div>
						<div style="float: left;">
							<div class="admin_option_name">
								{$LANG_FIELD_SEO_NAME}<span class="red_asterisk">*</span>
							</div>
							<div class="admin_option_description">
								{$LANG_FIELD_SEO_NAME_DESCR}
							</div>
							<input type=text name="seo_name" id="seo_name" value="{$field->seo_name}" size="40" class="admin_option_input">
						</div>
						<div style="clear: both"></div>
						<br />
						<div class="admin_option_name" >
							{$LANG_FIELD_FRONTEND_NAME}
							{translate_content table='content_fields' field='frontend_name' row_id=$field_id}
						</div>
						<div class="admin_option_description">
							{$LANG_FIELD_FRONTEND_NAME_DESCR}
						</div>
						<input type=text name="frontend_name" value="{$field->frontend_name}" size="40" class="admin_option_input">
					</div>

					{if $field_id =='new' }
					<div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_FIELD_TYPE}<span class="red_asterisk">*</span>
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_FIELD_TYPE_DESCR}
                          </div>
                          <select name="type" style="width:200px">
                          {foreach from=$field_types item=type key=type_key}
                          		<option value="{$type_key}" {if $field->type == $type_key}selected{/if}>{$type->name}</option>
                          {/foreach}
                          </select>
                     </div>
                     {/if}
                     
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_FIELD_DESCRIPTION}
                          	{translate_content table='content_fields' field='description' row_id=$field_id field_type='text'}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_FIELD_DESCRIPTION_DESCR}
                          </div>
                          <textarea name="description" rows="5" cols="80">{$field->description}</textarea>
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_FIELD_REQUIRED}
                          </div>
                          <label><input type="checkbox" name="required" value="1" {if $field->required == 1}checked{/if}> {$LANG_REQUIRED_TH}</label>
                     </div>
                     
                     <label class="block_title">{$LANG_FIELD_VISIBILITY}</label>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_FIELD_VISIBILITY_INDEX}
                          </div>
                          <label><input type="checkbox" name="v_index_page" value="1" {if $field->v_index_page == 1}checked{/if}> {$LANG_FIELD_VISIBILE}</label>
                          <div class="admin_option_name">
                          	{$LANG_FIELD_VISIBILITY_TYPES}
                          </div>
                          <label><input type="checkbox" name="v_types_page" value="1" {if $field->v_types_page == 1}checked{/if}> {$LANG_FIELD_VISIBILE}</label>
                          <div class="admin_option_name">
                          	{$LANG_FIELD_VISIBILITY_CATEGORIES}
                          </div>
                          <label><input type="checkbox" name="v_categories_page" value="1" {if $field->v_categories_page == 1}checked{/if}> {$LANG_FIELD_VISIBILE}</label>
                          <div class="admin_option_name">
                          	{$LANG_FIELD_VISIBILITY_SEARCH}
                          </div>
                          <label><input type="checkbox" name="v_search_page" value="1" {if $field->v_search_page == 1}checked{/if}> {$LANG_FIELD_VISIBILE}</label>
                     </div>
                     <input class="button save_button" type=submit name="submit" value="{if $field_id != 'new'}{$LANG_BUTTON_SAVE_CHANGES}{else}{$LANG_CREATE_FIELD}{/if}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}