{include file="backend/admin_header.tpl"}
{assign var="node_id" value=$node->id()}

                <div class="content">
                	<h3>{$LANG_VIEW_CONTENT_PAGE} "{$node->title()}"</h3>

                    <a href="{$VH->site_url("admin/pages/create")}" title="{$LANG_CREATE_PAGE}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
                     <a href="{$VH->site_url("admin/pages/create")}">{$LANG_CREATE_PAGE}</a>&nbsp;&nbsp;&nbsp;
                     
                     <a href="{$VH->site_url("admin/pages/edit/$node_id")}" title="{$LANG_EDIT_CONTENT_PAGE}"><img src="{$public_path}/images/buttons/page_edit.png" /></a>
                     <a href="{$VH->site_url("admin/pages/edit/$node_id")}">{$LANG_EDIT_CONTENT_PAGE}</a>&nbsp;&nbsp;&nbsp;
                     
                     <a href="{$VH->site_url("admin/pages/delete/$node_id")}" title="{$LANG_DELETE_CONTENT_PAGE}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
                     <a href="{$VH->site_url("admin/pages/delete/$node_id")}">{$LANG_DELETE_CONTENT_PAGE}</a>&nbsp;&nbsp;&nbsp;

                     <br />
                     <br />
                     
                    <div class="admin_option">
						<h4>{$LANG_CONTENT_PAGE_INFO}</h4>
						<table>
							<tr>
								<td class="table_left_side">{$LANG_URL_TD}:</td>
								<td class="table_right_side">{$node->url()}</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_TITLE_TD}:</td>
								<td class="table_right_side">{$node->title()}</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_CREATION_DATE_TD}:</td>
								<td class="table_right_side">{$node->creationDate()|date_format:"%D %T"}</td>
							</tr>
						</table>
					</div>

		            {if $node->content_fields->fieldsCount() && $node->content_fields->isAnyValue()}
						<label class="block_title">{$LANG_ADDITIONAL_FIELDS}</label>
						<div class="admin_option">
		                   	{$node->content_fields->outputMode()}
						</div>
                    {/if}
                </div>

{include file="backend/admin_footer.tpl"}