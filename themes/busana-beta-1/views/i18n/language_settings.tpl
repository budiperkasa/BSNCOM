{include file="backend/admin_header.tpl"}
{assign var="language_id" value=$language->id}

{if $language->id == 'new'}
				<script type="text/javascript">
					$(document).ready(function(){ldelim}
						$(".save_button").click(function() {ldelim}
							if (!$("#manual").attr('checked'))
	        					ajax_loader_show("Translating...");
	        			{rdelim});
        			{rdelim});
				</script>
{/if}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{if $language->id == 'new'}{$LANG_CREATE_LANG}{else}{$LANG_EDIT_LANG}{/if}</h3>

                     {if $language->id !='new' }
                     <a href="{$VH->site_url("admin/languages/create")}" title="{$LANG_CREATE_LANG_OPTION}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
                     <a href="{$VH->site_url("admin/languages/create")}">{$LANG_CREATE_LANG_OPTION}</a>&nbsp;&nbsp;&nbsp;

                     <a href="{$VH->site_url("admin/languages/delete/$language_id")}" title="{$LANG_DELETE_LANG_OPTION}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
                     <a href="{$VH->site_url("admin/languages/delete/$language_id")}">{$LANG_DELETE_LANG_OPTION}</a>&nbsp;&nbsp;&nbsp;
                     <div class="px10"></div>
                     {/if}

                     <form id="save_lang_form" action="" method="post">
                     {if $language->id !='new' }
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_LANG_ACTIVE}
                          </div>
                          <input type="checkbox" name="active" value="{$language->active}" class="admin_option_input" {if $language->active}checked{/if} />&nbsp;{$LANG_ENABLED}
                     </div>
                     {/if}
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_LANG_NAME}<span class="red_asterisk">*</span>
                          </div>
                          <div class="admin_option_description">
                               {$LANG_LANG_NAME_DESCR}
                          </div>
                          <input type=text name="name" value="{$language->name}" size="25" class="admin_option_input">
                     </div>
                     {if $language->code != 'en'}
                     <div class="admin_option">
                          <div class="admin_option_name">
                               {$LANG_LANG_CODE}<span class="red_asterisk">*</span>
                          </div>
                          <div class="admin_option_description">
                               {$LANG_LANG_CODE_DESCR_1} <a href="http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes" target="_blank">ISO-639</a> (5 {$LANG_LANG_CODE_DESCR_2}). {$LANG_LANG_CODE_DESCR_3} <a href="http://code.google.com/intl/ru/apis/language/translate/v1/reference.html#translatableLanguages" target="_blank">{$LANG_LANG_CODE_DESCR_4}</a>
                          </div>
                          <input type=text name="code" value="{$language->code}" size="2" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                               {$LANG_LANG_DB_CODE}<span class="red_asterisk">*</span>
                          </div>
                          <div class="admin_option_description">
                               {$LANG_LANG_DB_CODE_DESCR}. 2 {$LANG_SYMBOLS_MAX}.
                          </div>
                          <input type=text name="db_code" value="{$language->db_code}" size="1" class="admin_option_input">
                     </div>
                     {/if}
                     <div class="admin_option">
						<div class="admin_option_name">
							{$LANG_LANG_CUSTOM_THEME}<span class="red_asterisk">*</span>
						</div>
						<select name="custom_theme">
							{foreach from=$themes_list item=item}
								<option value="{$item}" {if $language->custom_theme == $item}selected{/if}>{$item}</option>
							{/foreach}
						</select>
					 </div>
					 <div class="admin_option">
						<div class="admin_option_name">
							{$LANG_LANG_DECIMALS_SEPARATOR}<span class="red_asterisk">*</span>
						</div>
						<select name="decimals_separator">
							<option value="." {if $language->decimals_separator == '.'}selected{/if}>{$LANG_LANG_DECIMALS_SEPARATOR_DOT}</option>
							<option value="," {if $language->decimals_separator == ','}selected{/if}>{$LANG_LANG_DECIMALS_SEPARATOR_COMMA}</option>
						</select>
					 </div>
					 <div class="admin_option">
						<div class="admin_option_name">
							{$LANG_LANG_THOUSANDS_SEPARATOR}<span class="red_asterisk">*</span>
						</div>
						<select name="thousands_separator">
							<option value="" {if $language->thousands_separator == ''}selected{/if}>{$LANG_LANG_THOUSANDS_SEPARATOR_NOSEP}</option>
							<option value="." {if $language->thousands_separator == '.'}selected{/if}>{$LANG_LANG_THOUSANDS_SEPARATOR_DOT}</option>
							<option value="," {if $language->thousands_separator == ','}selected{/if}>{$LANG_LANG_THOUSANDS_SEPARATOR_COMMA}</option>
							<option value=" " {if $language->thousands_separator == ' '}selected{/if}>{$LANG_LANG_THOUSANDS_SEPARATOR_SPACE}</option>
						</select>
					 </div>
					 <div class="admin_option">
						<div class="admin_option_name">
							{$LANG_LANG_DATE_FORMAT}<span class="red_asterisk">*</span>
						</div>
						<div class="admin_option_description">
							{$LANG_LANG_DATE_TIME_FORMAT_DESCR_1} <a href="http://www.smarty.net/docsv2/en/language.modifier.date.format" target="_blank">{$LANG_LANG_DATE_TIME_FORMAT_DESCR_2}</a>
                        </div>
						<input type=text name="date_format" value="{$language->date_format}" size="10" class="admin_option_input">
					 </div>
					 <div class="admin_option">
						<div class="admin_option_name">
							{$LANG_LANG_TIME_FORMAT}<span class="red_asterisk">*</span>
						</div>
						<div class="admin_option_description">
							{$LANG_LANG_DATE_TIME_FORMAT_DESCR_1} <a href="http://www.smarty.net/docsv2/en/language.modifier.date.format" target="_blank">{$LANG_LANG_DATE_TIME_FORMAT_DESCR_2}</a>
                        </div>
						<input type=text name="time_format" value="{$language->time_format}" size="10" class="admin_option_input">
					 </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_LANG_FLAG}<span class="red_asterisk">*</span>
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_LANG_FLAG_DESCR} '/themes/{ldelim}your_theme_folder{rdelim}/images/flags/'
                          </div>
                          <select name="flag">
                          	<option value="-1">{$LANG_SELECT_FLAG}</option>
                          	{if $flags_images|@count > 0}
                          		{foreach from=$flags_images item=flag}
                          		<option value="{$flag}" {if $language->flag == $flag}selected{/if}>{$flag}</option>
                          		{/foreach}
                          	{/if}
                          </select>
                     </div>
                     <input class="button save_button" type="submit" name="submit" value="{if $language->id != 'new'}{$LANG_BUTTON_SAVE_CHANGES}{else}{$LANG_BUTTON_CREATE_LANG}{/if}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}