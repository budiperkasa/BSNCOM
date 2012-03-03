{include file="backend/admin_header.tpl"}
{assign var="node_id" value=$node->id()}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{if $node->id() == 'new'}{$LANG_CREATE_CONTENT_PAGE}{else}{$LANG_EDIT_CONTENT_PAGE} "{$node->title()}"{/if}</h3>
                     
                     {if $node->id() != 'new'}
                     <a href="{$VH->site_url("admin/pages/create")}" title="{$LANG_CREATE_PAGE}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
                     <a href="{$VH->site_url("admin/pages/create")}">{$LANG_CREATE_PAGE}</a>&nbsp;&nbsp;&nbsp;
                     
                     <a href="{$VH->site_url("admin/pages/preview/$node_id")}" title="{$LANG_VIEW_CONTENT_PAGE}"><img src="{$public_path}/images/buttons/page.png" /></a>
                     <a href="{$VH->site_url("admin/pages/preview/$node_id")}">{$LANG_VIEW_CONTENT_PAGE}</a>&nbsp;&nbsp;&nbsp;
                     
                     <a href="{$VH->site_url("admin/pages/delete/$node_id")}" title="{$LANG_DELETE_CONTENT_PAGE}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
                     <a href="{$VH->site_url("admin/pages/delete/$node_id")}">{$LANG_DELETE_CONTENT_PAGE}</a>&nbsp;&nbsp;&nbsp;
                     
                     <br />
                     <br />
                     {/if}
                     
                     <form action="" method="post">
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_PAGE_PUBLIC_URL}<span class="red_asterisk">*</span>
                          </div>
                          <input type=text name="url" value="{$node->url()}" size="50" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                               {$LANG_PAGE_TITLE}<span class="red_asterisk">*</span>
                               {translate_content table='content_pages' field='title' row_id=$node_id}
                          </div>
                          <input type=text name="title" value="{$node->title()}" size="50" class="admin_option_input">
                     </div>
                     <div class="admin_option">
						<div class="admin_option_name">
							{$LANG_ENABLE_LINK_IN_MENU}
						</div>
						<label><input type="checkbox" name="in_menu" value="1" {if $node->inMenu()}checked{/if} /> {$LANG_ENABLE}</label>
					 </div>

					 <div class="admin_option">
                     	<div class="admin_option_name">
                          	{$LANG_META_TITLE}
                          	{translate_content table='content_pages' field='meta_title' row_id=$node_id}
                        </div>
                        <div class="admin_option_description">
                        	{$LANG_META_TITLE_DESCR}
                        </div>
                        <input type=text name="meta_title" value="{$node->meta_title()}" size="60" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                     	<div class="admin_option_name">
                          	{$LANG_META_DESCRIPTION}
                          	{translate_content table='content_pages' field='meta_description' row_id=$node_id field_type='text'}
                        </div>
                        <div class="admin_option_description">
                        	{$LANG_META_TITLE_DESCR}
                        </div>
                        <textarea name="meta_description" cols="60" rows="5">{$node->meta_description()}</textarea>
                     </div>

                     {$node->inputMode()}
                     <input class="button save_button" type=submit name="submit" value="{if $node->id() != 'new'}{$LANG_BUTTON_SAVE_CHANGES}{else}{$LANG_BUTTON_CREATE_NEW_PAGE}{/if}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}