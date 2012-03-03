{include file="backend/admin_header.tpl"}
{assign var="loc_level_id" value=$loc_level->id}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{if $loc_level_id != 'new'}{$LANG_EDIT_LOCATIONS_LEVEL_TITLE} "{$loc_level->name}"{else}{$LANG_CREATE_LOCATIONS_LEVEL_TITLE}{/if}</h3>
                     {if $loc_level_id !='new' }
                     <a href="{$VH->site_url("admin/locations/levels/create")}" title="{$LANG_CREATE_LOC_LEVEL_OPTION}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
                     <a href="{$VH->site_url("admin/locations/levels/create")}">{$LANG_CREATE_LOC_LEVEL_OPTION}</a>&nbsp;&nbsp;&nbsp;
                     <a href="{$VH->site_url("admin/locations/levels/delete/$loc_level_id")}" title="{$LANG_DELETE_LOC_LEVEL_OPTION}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
                     <a href="{$VH->site_url("admin/locations/levels/delete/$loc_level_id")}">{$LANG_DELETE_LOC_LEVEL_OPTION}</a>&nbsp;&nbsp;&nbsp;
                     <br />
                     <br />
                     {/if}

                     <form action="" method="post">
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_LOC_LEVEL_NAME}<span class="red_asterisk">*</span>
                          	{translate_content table='locations_levels' field='name' row_id=$loc_level_id}
                          </div>
                          <input type=text name="name" value="{$loc_level->name}" size="40" class="admin_option_input">
                     </div>
                     <input class="button save_button" type=submit name="submit" value="{if $loc_level_id != 'new'}{$LANG_BUTTON_SAVE_CHANGES}{else}{$LANG_BUTTON_CREATE_LOC_LEVEL}{/if}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}