{include file="backend/admin_header.tpl"}
{assign var="field_id" value=$field->id}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{$LANG_CONFIGURE_FIELD_1} "{$field->type_name}" {$LANG_CONFIGURE_FIELD_2} "{$field->name}"</h3>
                     <h4>{$LANG_READ_PHP_DATE_1} <a href="http://ru.php.net/manual/en/function.date.php" target="_blank">{$LANG_READ_PHP_DATE_2}</a> {$LANG_READ_PHP_DATE_3}</h4>

                     <a href="{$VH->site_url("admin/fields/edit/$field_id")}" title="{$LANG_EDIT_FIELD_OPTION}"><img src="{$public_path}/images/buttons/page_edit.png"></a>
					 <a href="{$VH->site_url("admin/fields/edit/$field_id")}">{$LANG_EDIT_FIELD_OPTION}</a>&nbsp;&nbsp;&nbsp;&nbsp;

					 {if $field->search_configuration_page}
                       	<a href="{$VH->site_url("admin/fields/configure_search/$field_id")}" title="{$LANG_CONFIGURE_SEARCH_FIELD_OPTION}"><img src="{$public_path}images/buttons/page_find.png"></a>
                       	<a href="{$VH->site_url("admin/fields/configure_search/$field_id")}">{$LANG_CONFIGURE_SEARCH_FIELD_OPTION}</a>&nbsp;&nbsp;&nbsp;&nbsp;
                     {/if}
					 
					 <a href="{$VH->site_url("admin/fields/delete/$field_id")}"><img src="{$public_path}/images/buttons/page_delete.png"></a>
					 <a href="{$VH->site_url("admin/fields/delete/$field_id")}">{$LANG_DELETE_FIELD_OPTION}</a>&nbsp;&nbsp;&nbsp;&nbsp;
					 <br />
					 <br />

                     <form action="" method="post">
					 <input type="hidden" name="id" value="{$field->id}"> 
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_DATETIME_FORMAT}<span class="red_asterisk">*</span>
                          </div>
                          <div class="admin_option_description" >
                          	{$LANG_DATETIME_FORMAT_DESCR}
                          </div>
                          <input type=text name="format" value="{$format}" size="10" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_ENABLE_TIME}
                          </div>
                          <input type=checkbox name="enable_time" value="1" {if $enable_time}checked{/if}> {$LANG_ENABLE}
                     </div>
                     <br/>
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}