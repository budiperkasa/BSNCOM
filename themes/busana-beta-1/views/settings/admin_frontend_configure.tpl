{include file="backend/admin_header.tpl"}

{if $type}
<script language="JavaScript" type="text/javascript">
$(document).ready(function() {ldelim}
	$(".levels_visible").change( function() {ldelim}
		var levels_list = [];
		$(".levels_visible").each(function() {ldelim}
			if ($(this).is(':checked'))
				levels_list.push($(this).attr('id').replace("level_", ""));
		{rdelim});
		$("#serialised_levels").val(levels_list.join(','));
	{rdelim});
{rdelim});
</script>
{/if}
                <div class="content">
                	 {if $type}
                     <h3>{$LANG_EDIT_LISTINGS_VIEW_1} "{$type->name}" {$LANG_EDIT_LISTINGS_VIEW_2} "{$listings_view->page_name}"</h3>
                     {else}
                     <h3>{$LANG_FRONTEND_SETTINGS_PAGE} "{$listings_view->page_name}"</h3>
                     {/if}

                     <form action="" method="post">
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_LISTING_VIEW_TH}<span class="red_asterisk">*</span>
                          </div>
                          {if $listings_view->page_key != 'quicklist'}
                          <label><input type="radio" name="view" value="semitable" {if $listings_view->view == 'semitable'} checked {/if} /> {$LANG_FRONTEND_SETTING_SEMITABLE}</label>
                          <label><input type="radio" name="view" value="full" {if $listings_view->view == 'full'} checked {/if} /> {$LANG_FRONTEND_SETTING_FULL}</label>
                          <label><input type="radio" name="view" value="short" {if $listings_view->view == 'short'} checked {/if} /> {$LANG_FRONTEND_SETTING_SHORT}</label>
                          {else}
                          <label><input type="radio" name="view" value="quicklist" {if $listings_view->view == 'quicklist'} checked {/if} /> {$LANG_FRONTEND_SETTING_QUICK_LIST}</label>
                          {/if}
                     </div>
                     <div class="px10"></div>
                     <div class="admin_option">
						<div class="admin_option_name">
							{$LANG_LISTING_FORMAT}<span class="red_asterisk">*</span>
						</div>
						<div class="admin_option_description">
							{$LANG_LISTING_FORMAT_DESCR}
						</div>
						<input type=text name="format" value="{$listings_view->format}" size="5" />
					 </div>
                     <div class="px10"></div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_DEFAULT_LISTINGS_ORDER}<span class="red_asterisk">*</span>
                          </div>
                          <label><input type="radio" name="order_by" value="l.creation_date" {if $listings_view->order_by == 'l.creation_date'} checked {/if} /> {$LANG_SEARCH_CREATION_DATE}</label>
                          <label><input type="radio" name="order_by" value="l.title" {if $listings_view->order_by == 'l.title'} checked {/if} /> {$LANG_SEARCH_LISTING_TITLE}</label>
                          <label><input type="radio" name="order_by" value="lev.order_num" {if $listings_view->order_by == 'lev.order_num'} checked {/if} /> {$LANG_SEARCH_INFO_VALUE}</label>
                          <label><input type="radio" name="order_by" value="rating" {if $listings_view->order_by == 'rating'} checked {/if} /> {$LANG_SEARCH_RATING}</label>
                          <label><input type="radio" name="order_by" value="rev_count" {if $listings_view->order_by == 'rev_count'} checked {/if} /> {$LANG_SEARCH_REVIEWS_COUNT}</label>
                          <label><input type="radio" name="order_by" value="rev_last" {if $listings_view->order_by == 'rev_last'} checked {/if} /> {$LANG_SEARCH_LAST_REVIEW_DATE}</label>
                          <label><input type="radio" name="order_by" value="random" {if $listings_view->order_by == 'random'} checked {/if} /> {$LANG_SEARCH_RANDOM}</label>
                          <div class="px5"></div>
                          <div class="admin_option_name">
                          	{$LANG_DEFAULT_LISTINGS_ORDER_DIRECTION}<span class="red_asterisk">*</span>
                          </div>
                          <label><input type="radio" name="order_direction" value="asc" {if $listings_view->order_direction == 'asc'} checked {/if} /> {$LANG_SORT_ASCENDING}</label>
                          <label><input type="radio" name="order_direction" value="desc" {if $listings_view->order_direction == 'desc'} checked {/if} /> {$LANG_SORT_DESCENDING}</label>
                     </div>
                     <div class="px10"></div>
                     {if $type}
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_LISTING_LEVELS_VISIBLE}<span class="red_asterisk">*</span>
                          </div>
                          {foreach from=$type->levels item=level}
                          <label><input type="checkbox" id="level_{$level->id}" class="levels_visible" {if $VH->in_array($level->id, $listings_view->levels_visible) || $listings_view->levels_visible|@count == 0} checked {/if} /> {$level->name}</label>
                          {/foreach}
                          <input type="hidden" name="serialised_levels" id="serialised_levels" value="{$VH->implode(',', $listings_view->levels_visible)}">
                     </div>
                     {/if}
                     <div class="px10"></div>
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}