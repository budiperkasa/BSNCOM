{include file="backend/admin_header.tpl"}
{assign var="field_id" value=$field->id}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{$LANG_CONFIGURE_SEARCH_FIELD_1} "{$field->type_name}" {$LANG_CONFIGURE_SEARCH_FIELD_2} "{$field->name}"</h3>
                     <a href="{$VH->site_url("admin/fields/edit/$field_id")}" title="{$LANG_EDIT_FIELD_OPTION}"><img src="{$public_path}/images/buttons/page_edit.png"></a>
					 <a href="{$VH->site_url("admin/fields/edit/$field_id")}">{$LANG_EDIT_FIELD_OPTION}</a>&nbsp;&nbsp;&nbsp;&nbsp;
                     {if $field->configuration_page}
						<a href="{$VH->site_url("admin/fields/configure/$field_id")}" title="{$LANG_CONFIGURE_FIELD_OPTION}"><img src="{$public_path}/images/buttons/page_gear.png"></a>
						<a href="{$VH->site_url("admin/fields/configure/$field_id")}">{$LANG_CONFIGURE_FIELD_OPTION}</a>&nbsp;&nbsp;&nbsp;&nbsp;
					 {/if}
					 <a href="{$VH->site_url("admin/fields/delete/$field_id")}"><img src="{$public_path}/images/buttons/page_delete.png"></a>
					 <a href="{$VH->site_url("admin/fields/delete/$field_id")}">{$LANG_DELETE_FIELD_OPTION}</a>&nbsp;&nbsp;&nbsp;&nbsp;
					 <br />
					 <br />

                     <form action="" method="post">
					 <input type="hidden" name="id" value="{$field_id}"> 
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_SELECT_SEARCH_TYPE}
                          </div>
                          <div class="admin_option_description" >
                          	{$LANG_SELECT_SEARCH_TYPE_NUM_DESCR}
                          </div>
                          <label><input type=radio name="search_type" value="exact_match" {if $search_type == 'exact_match'}checked{/if} > {$LANG_EXACT_MATCH}</label>
                          <label style="float:left"><input type=radio name="search_type" value="min_max" {if $search_type == 'min_max'}checked{/if} /> {$LANG_MIN_MAX_TYPE}</label><div style="float:left; padding: 4px 0;">&nbsp;&nbsp;(<a href="{$VH->site_url("admin/fields/configure_search/$field_id/manage_options/")}">{$LANG_MANAGE_OPTIONS}</a>)</div>
                          <div class="clear_float"></div>
                     </div>
                     <br/>
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}