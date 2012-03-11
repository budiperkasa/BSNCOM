{include file="backend/admin_header.tpl"}
{assign var="field_id" value=$field->id}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{$LANG_CONFIGURE_SEARCH_FIELD_1} "{$field->type_name}" {$LANG_CONFIGURE_SEARCH_FIELD_2} "{$field->name}"</h3>

                     <form action="" method="post">
					 <input type="hidden" name="id" value="{$field_id}"> 
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_SELECT_SEARCH_TYPE}
                          </div>
                          <div class="admin_option_description" >
                          	{$LANG_SELECT_SEARCH_TYPE_STR_DESCR}
                          </div>
                          <label><input type=radio name="search_type" value="connect_what_field" {if $search_type == 'connect_what_field'}checked{/if} /> {$LANG_WHAT_FIELD_SEARCH_TYPE}</label>
                          <label><input type=radio name="search_type" value="self_search" {if $search_type == 'self_search'}checked{/if} > {$LANG_SELF_SEARCH_TYPE}</label>
                     </div>
                     <br/>
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}