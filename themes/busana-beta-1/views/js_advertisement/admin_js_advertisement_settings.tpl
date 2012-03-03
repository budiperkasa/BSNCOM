{include file="backend/admin_header.tpl"}
{assign var="js_advertisement_id" value=$js_advertisement->id}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{if $js_advertisement_id != 'new'}{$LANG_EDIT_JSADVERTISEMENT} "{$js_advertisement_name}"{else}{$LANG_CREATE_JSADVERTISEMENT}{/if}</h3>

                     {if $js_advertisement_id !='new' }
                     <a href="{$VH->site_url("admin/js_advertisement/create")}" title="{$LANG_CREATE_JSADVERTISEMENT_BLOCK}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
                     <a href="{$VH->site_url("admin/js_advertisement/create")}">{$LANG_CREATE_JSADVERTISEMENT_BLOCK}</a>&nbsp;&nbsp;&nbsp;
                     <a href="{$VH->site_url("admin/js_advertisement/delete/$js_advertisement_id")}" title="{$LANG_DELETE_JSADVERTISEMENT_BLOCK}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
                     <a href="{$VH->site_url("admin/js_advertisement/delete/$js_advertisement_id")}">{$LANG_DELETE_JSADVERTISEMENT_BLOCK}</a>&nbsp;&nbsp;&nbsp;
                     <br />
                     <br />
                     {/if}

                     <form action="" method="post">
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_JSADVERTISEMENT_BLOCK_NAME_TH}<span class="red_asterisk">*</span>
                          </div>
                          <input type=text name="name" value="{$js_advertisement->name}" size="40" class="admin_option_input">
                     </div>

                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_JSADVERTISEMENT_BLOCK_MODE_TH}<span class="red_asterisk">*</span>
                          </div>
                          <input type="text" name="mode" value="{$js_advertisement->mode}" size="7" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_JSADVERTISEMENT_BLOCK_SELECTOR_TH}<span class="red_asterisk">*</span>
                          </div>
                          <input type="text" name="selector" value="{$js_advertisement->selector}" size="40" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_JSADVERTISEMENT_CODE}<span class="red_asterisk">*</span>
                          </div>
                          <textarea type="text" name="code" cols="80" rows="15">{$js_advertisement->code}</textarea>
                     </div>
                     <input class="button save_button" type=submit name="submit" value="{if $js_advertisement_id != 'new'}{$LANG_BUTTON_SAVE_CHANGES}{else}{$LANG_BUTTON_CREATE_JSADVERTISEMENT}{/if}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}