{include file="backend/admin_header.tpl"}
{assign var="banners_block_id" value=$banners_block->id}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{if $banners_block_id != 'new'}{$LANG_BANNERS_BLOCKS_EDIT_TITLE} "{$banners_block->name}"{else}{$LANG_BANNERS_BLOCKS_CREATE_TITLE}{/if}</h3>

                     {if $banners_block_id !='new' }
                     <div class="admin_top_menu_cell">
	                     <a href="{$VH->site_url("admin/banners_blocks/create")}" title="{$LANG_CREATE_BANNERS_BLOCK}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
	                     <a href="{$VH->site_url("admin/banners_blocks/create")}">{$LANG_CREATE_BANNERS_BLOCK}</a>
	                 </div>
	                 <div class="admin_top_menu_cell">
	                     <a href="{$VH->site_url("admin/banners_blocks/delete/$banners_block_id")}" title="{$LANG_DELETE_BANNERS_BLOCK}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
	                     <a href="{$VH->site_url("admin/banners_blocks/delete/$banners_block_id")}">{$LANG_DELETE_BANNERS_BLOCK}</a>
                     </div>
                     <div class="clear_float"></div>
                     <div class="px10"></div>
                     {/if}

                     <form action="" method="post">
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_BANNERS_BLOCK_NAME_TH}<span class="red_asterisk">*</span>
                          	{translate_content table='banners_blocks' field='name' row_id=$banners_block_id}
                          </div>
                          <input type=text name="name" value="{$banners_block->name}" size="75" class="admin_option_input">
                     </div>

                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_BANNERS_BLOCK_MODE_TH}<span class="red_asterisk">*</span>
                          </div>
                          <select name="mode">
                          	<option value="post" {if $banners_block->mode == 'post'}selected{/if}>post</option>
                          	<option value="pre" {if $banners_block->mode == 'pre'}selected{/if}>pre</option>
                          	<option value="postouter" {if $banners_block->mode == 'postouter'}selected{/if}>postouter</option>
                          	<option value="preouter" {if $banners_block->mode == 'preouter'}selected{/if}>preouter</option>
                          	<option value="replace" {if $banners_block->mode == 'replace'}selected{/if}>replace</option>
                          </select>
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_BANNERS_BLOCK_SELECTOR_TH}<span class="red_asterisk">*</span>
                          </div>
                          <input type="text" name="selector" value="{$banners_block->selector}" size="40" class="admin_option_input">
                     </div>
                     
                     <div class="admin_option">
                         <div class="admin_option_name">
                         	{$LANG_BANNERS_BLOCK_SIZE}<span class="red_asterisk">*</span>
                         </div>
                         <div class="admin_option_description">
                         	{$LANG_BANNERS_BLOCK_SIZE_DESCR}
                         </div>
                         {$LANG_WIDTH}, {$LANG_PX}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$LANG_HEIGHT}, {$LANG_PX}<br>
                         <input type=text name="banner_width" value="{$banners_block->explodeSize('block_size', 0)}" size="4" class="admin_option_input">&nbsp;&nbsp;&nbsp;&nbsp;
                         <input type=text name="banner_height" value="{$banners_block->explodeSize('block_size', 1)}" size="4" class="admin_option_input">
                     </div>

                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_BANNERS_BLOCK_ACTIVE_PERIOD}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_BANNERS_BLOCK_ACTIVE_PERIOD_DESCR}
                          </div>
                          <select name="active_years" class="no_min_width">
                          	<option value="0" {if $banners_block->active_years == 0}selected{/if}>0</option>
                          	<option value="1" {if $banners_block->active_years == 1}selected{/if}>1</option>
                          	<option value="2" {if $banners_block->active_years == 2}selected{/if}>2</option>
                          	<option value="3" {if $banners_block->active_years == 3}selected{/if}>3</option>
                          	<option value="4" {if $banners_block->active_years == 4}selected{/if}>4</option>
                          	<option value="5" {if $banners_block->active_years == 5}selected{/if}>5</option>
                          	<option value="6" {if $banners_block->active_years == 6}selected{/if}>6</option>
                          	<option value="7" {if $banners_block->active_years == 7}selected{/if}>7</option>
                          	<option value="8" {if $banners_block->active_years == 8}selected{/if}>8</option>
                          	<option value="9" {if $banners_block->active_years == 9}selected{/if}>9</option>
                          	<option value="10" {if $banners_block->active_years == 10}selected{/if}>10</option>
                          </select> {$LANG_YEARS}
                          &nbsp;&nbsp;
                          <select name="active_months" class="no_min_width">
                          	<option value="0" {if $banners_block->active_months == 0}selected{/if}>0</option>
                          	<option value="1" {if $banners_block->active_months == 1}selected{/if}>1</option>
                          	<option value="2" {if $banners_block->active_months == 2}selected{/if}>2</option>
                          	<option value="3" {if $banners_block->active_months == 3}selected{/if}>3</option>
                          	<option value="4" {if $banners_block->active_months == 4}selected{/if}>4</option>
                          	<option value="5" {if $banners_block->active_months == 5}selected{/if}>5</option>
                          	<option value="6" {if $banners_block->active_months == 6}selected{/if}>6</option>
                          	<option value="7" {if $banners_block->active_months == 7}selected{/if}>7</option>
                          	<option value="8" {if $banners_block->active_months == 8}selected{/if}>8</option>
                          	<option value="9" {if $banners_block->active_months == 9}selected{/if}>9</option>
                          	<option value="10" {if $banners_block->active_months == 10}selected{/if}>10</option>
                          	<option value="11" {if $banners_block->active_months == 11}selected{/if}>11</option>
                          	<option value="12" {if $banners_block->active_months == 12}selected{/if}>12</option>
                          </select> {$LANG_MONTHS}
                          <select name="active_days" class="no_min_width">
                          	<option value="0" {if $banners_block->active_days == 0}selected{/if}>0</option>
                          	<option value="1" {if $banners_block->active_days == 1}selected{/if}>1</option>
                          	<option value="2" {if $banners_block->active_days == 2}selected{/if}>2</option>
                          	<option value="3" {if $banners_block->active_days == 3}selected{/if}>3</option>
                          	<option value="4" {if $banners_block->active_days == 4}selected{/if}>4</option>
                          	<option value="5" {if $banners_block->active_days == 5}selected{/if}>5</option>
                          	<option value="6" {if $banners_block->active_days == 6}selected{/if}>6</option>
                          	<option value="7" {if $banners_block->active_days == 7}selected{/if}>7</option>
                          	<option value="8" {if $banners_block->active_days == 8}selected{/if}>8</option>
                          	<option value="9" {if $banners_block->active_days == 9}selected{/if}>9</option>
                          	<option value="10" {if $banners_block->active_days == 10}selected{/if}>10</option>
                          	<option value="11" {if $banners_block->active_days == 11}selected{/if}>11</option>
                          	<option value="12" {if $banners_block->active_days == 12}selected{/if}>12</option>
                          	<option value="13" {if $banners_block->active_days == 13}selected{/if}>13</option>
                          	<option value="14" {if $banners_block->active_days == 14}selected{/if}>14</option>
                          	<option value="15" {if $banners_block->active_days == 15}selected{/if}>15</option>
                          	<option value="16" {if $banners_block->active_days == 16}selected{/if}>16</option>
                          	<option value="17" {if $banners_block->active_days == 17}selected{/if}>17</option>
                          	<option value="18" {if $banners_block->active_days == 18}selected{/if}>18</option>
                          	<option value="19" {if $banners_block->active_days == 19}selected{/if}>19</option>
                          	<option value="20" {if $banners_block->active_days == 20}selected{/if}>20</option>
                          	<option value="21" {if $banners_block->active_days == 21}selected{/if}>21</option>
                          	<option value="22" {if $banners_block->active_days == 22}selected{/if}>22</option>
                          	<option value="23" {if $banners_block->active_days == 23}selected{/if}>23</option>
                          	<option value="24" {if $banners_block->active_days == 24}selected{/if}>24</option>
                          	<option value="25" {if $banners_block->active_days == 25}selected{/if}>25</option>
                          	<option value="26" {if $banners_block->active_days == 26}selected{/if}>26</option>
                          	<option value="27" {if $banners_block->active_days == 27}selected{/if}>27</option>
                          	<option value="28" {if $banners_block->active_days == 28}selected{/if}>28</option>
                          	<option value="29" {if $banners_block->active_days == 29}selected{/if}>29</option>
                          	<option value="30" {if $banners_block->active_days == 30}selected{/if}>30</option>
                          </select> {$LANG_DAYS}
                          &nbsp;&nbsp;
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_BANNERS_BLOCK_CLICKS_LIMIT}
                          </div>
                          <input type="text" name="clicks_limit" value="{$banners_block->clicks_limit}" size="4" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_BANNERS_BLOCK_LIMITATION_MODE}<span class="red_asterisk">*</span>
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_BANNERS_BLOCK_LIMITATION_MODE_DESCR}
                          </div>
                          <label><input type="radio" name="limit_mode" value="active_period" class="admin_option_input" {if $banners_block->limit_mode == 'active_period'}checked{/if}> {$LANG_BANNERS_ACTIVE_PERIOD_LIMITATION}</label>
                          <label><input type="radio" name="limit_mode" value="clicks" class="admin_option_input" {if $banners_block->limit_mode == 'clicks'}checked{/if}> {$LANG_BANNERS_CLICKS_LIMITATION}</label>
                          <label><input type="radio" name="limit_mode" value="both" class="admin_option_input" {if $banners_block->limit_mode == 'both'}checked{/if}> {$LANG_BANNERS_BOTH_LIMITATION_DESCR}</label>
                     </div>
                     <div class="admin_option">
                         <div class="admin_option_name">
                         	{$LANG_BANNERS_ALLOW_REMOTE_BANNERS}
                         </div>
                         <div class="admin_option_description">
                         	{$LANG_BANNERS_ALLOW_REMOTE_BANNERS_DESCR}
                         </div>
                         <label><input type="checkbox" name="allow_remote_banners" {if $banners_block->allow_remote_banners}checked{/if} /> {$LANG_ENABLED}</label>
                     </div>
                     
                     <input class="button save_button" type=submit name="submit" value="{if $banners_block_id != 'new'}{$LANG_BUTTON_SAVE_CHANGES}{else}{$LANG_BUTTON_CREATE_BANNERS_BLOCK}{/if}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}