{include file="backend/admin_header.tpl"}
{assign var="category_id" value=$category->id}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{if $category_id == 'new'}{$LANG_CREATE_CATEGORY}{else}{$LANG_EDIT_CATEGORY}{/if}</h3>
                     
                     {if $category_id != 'new'}
                     <a href="{$VH->site_url("admin/categories/create")}" title="{$LANG_NEW_CATEGORY_OPTION}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
                     <a href="{$VH->site_url("admin/categories/create")}">{$LANG_NEW_CATEGORY_OPTION}</a>&nbsp;&nbsp;&nbsp;

                     <a href="{$VH->site_url("admin/categories/delete/$category_id")}" title="{$LANG_DELETE_CATEGORY_OPTION}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
                     <a href="{$VH->site_url("admin/categories/delete/$category_id")}">{$LANG_DELETE_CATEGORY_OPTION}</a>&nbsp;&nbsp;&nbsp;
                     <br />
                     <br />
                     {/if}

                     <form action="" method="post">
                     <div class="admin_option">
                     	<div style="float: left;">
                          <div class="admin_option_name">
                          	{$LANG_CATEGORY_NAME}<span class="red_asterisk">*</span>
                          	{translate_content table='categories' field='name' row_id=$category_id}
                          </div>
                          <input type=text id="name" name="name" value="{$category->name}" size="45" class="admin_option_input">
                          &nbsp;&nbsp;<span class="seo_rewrite" title="{$LANG_WRITE_SEO_STYLE}"><img src="{$public_path}images/arrow_seo.gif"></span>&nbsp;&nbsp;
                        </div>
                        <div style="float: left;">
                          <div class="admin_option_name">
                          	{$LANG_CATEGORY_SEO_NAME}<span class="red_asterisk">*</span>
                          </div>
                          <input type=text id="seo_name" name="seo_name" value="{$category->seo_name}" size="45" class="admin_option_input">&nbsp;&nbsp;
                        </div>
                        <div style="clear: both"></div>
                     </div>
                     
                     <div class="admin_option">
                     	<a href="javascript: void(0);" onClick="$.jqURL.loc('{$VH->site_url("admin/categories/select_icons_for_categories/$category_id/")}', {ldelim}w:750,h:620,wintype:'_blank'{rdelim}); return false;">{$LANG_SELECT_ICONS}</a><br />
                     	<input type="hidden" id="selected_icons_serialized" name="selected_icons_serialized" value='{$category->selected_icons_serialized}'>
                     	<b>{$LANG_NOTE}:</b> <i>{$LANG_CATEGORIES_ICONS_SELECTION_NOTE}</i>
                     </div>

                     <div class="admin_option">
                     	<div class="admin_option_name">
                          	{$LANG_META_TITLE}
                          	{translate_content table='categories' field='meta_title' row_id=$category_id}
                        </div>
                        <div class="admin_option_description">
                        	{$LANG_META_TITLE_DESCR}
                        </div>
                        <input type=text name="meta_title" value="{$category->meta_title}" size="60" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                     	<div class="admin_option_name">
                          	{$LANG_META_DESCRIPTION}
                          	{translate_content table='categories' field='meta_description' row_id=$category_id field_type='text'}
                        </div>
                        <div class="admin_option_description">
                        	{$LANG_META_TITLE_DESCR}
                        </div>
                        <textarea name="meta_description" cols="60" rows="5">{$category->meta_description}</textarea>
                     </div>

                     <input class="button save_button" type=submit name="submit" value="{if $category_id != 'new'}{$LANG_BUTTON_SAVE_CHANGES}{else}{$LANG_BUTTON_CREATE_CATEGORY}{/if}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}