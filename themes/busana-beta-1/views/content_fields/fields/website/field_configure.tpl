{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_CONFIGURE_FIELD_1} "{$field->type_name}" {$LANG_CONFIGURE_FIELD_2} "{$field->name}"</h3>
                     <form action="" method="post">
					 <input type="hidden" name="id" value="{$field->id}"> 
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_ENABLE_REDIRECT}
                          </div>
                          <label><input type=checkbox name="enable_redirect" value="1" {if $enable_redirect}checked{/if}> {$LANG_ENABLED}</label>
                     </div>
                     <br/>
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}