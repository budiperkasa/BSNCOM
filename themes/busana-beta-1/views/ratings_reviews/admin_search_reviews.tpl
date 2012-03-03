{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
					var global_js_url = '{$base_url}';

					{assign var=random_id value=$VH->genRandomString()}
					var {$random_id}_mode = '{$mode}';
					
					var action_cmd;

					$(document).ready( function() {ldelim}
				        $('#search_login').autocomplete({ldelim}
							source: function(request, response) {ldelim}
								$.post("{$VH->site_url('admin/users/ajax_autocomplete_request/')}", {ldelim}query: request.term{rdelim}, function(data) {ldelim}
									response($.map(data.suggestions, function(item) {ldelim}
										return {ldelim}
											label: item,
											value: item
										{rdelim};
									{rdelim}));
								{rdelim}, "json");
							{rdelim},
							minLength: 2,
							delay: 500,
							select: function(event, ui) {ldelim}
								$(this).val(ui.item.label);
								return false;
							{rdelim}
						{rdelim});

				         $("#search_form").submit( function() {ldelim}
				           	if ({$random_id}_mode == 'single') {ldelim}
					        	if ($("#search_tmstmp_date_added").val() != '')
									global_js_url = global_js_url + "search_date_added" + '/' + $("#search_tmstmp_date_added").val() + '/';
							{rdelim}
							if ({$random_id}_mode == 'range') {ldelim}
								if ($("#from_tmstmp_date_added").val() != '')
									global_js_url = global_js_url + "search_from_date_added" + '/' + $("#from_tmstmp_date_added").val() + '/';
								if ($("#to_tmstmp_date_added").val() != '')
									global_js_url = global_js_url + "search_to_date_added" + '/' + $("#to_tmstmp_date_added").val() + '/';
							{rdelim}

							if ($("#search_login").val() != '')
		                		global_js_url = global_js_url + 'search_login/' + $("#search_login").val() + '/';
		                	global_js_url = global_js_url + "search_anonyms" + '/' + $("input[name=search_anonyms]:checked").val() + '/';
		                	if ($("#search_status").val() != -1)
		                		global_js_url = global_js_url + 'search_status/' + $("#search_status").val() + '/';

							window.location.href = global_js_url;
							return false;
						{rdelim});
					{rdelim});

					function submit_reviews_form()
	            	{ldelim}
	              		$("#reviews_form").attr("action", '{$VH->site_url('admin/reviews/')}' + action_cmd + '/');
	               		return true;
	            	{rdelim}
                </script>

                <div class="content">
                     <h3>{$LANG_SEARCH_REVIEWS_TITLE}</h3>
                     
                     <form id="search_form" action="" method="post">
                     	<div class="search_block">
	                     	<div class="search_item">
	                     		<label>{$LANG_SEARCH_LOGIN}:</label>
	                     		<input type="text" id="search_login" value="{$args.search_login}" style="width: 205px;" />
	                     	</div>
	                     	<div class="search_item">
	                     		<label><input type="radio" name="search_anonyms" value="anonyms" {if $args.search_anonyms == 'anonyms'}checked{/if}/> {$LANG_SEARCH_ONLY_ANONYMS}</label>
	                     		<label><input type="radio" name="search_anonyms" value="all" {if $args.search_anonyms == 'all' || $args.search_anonyms == null}checked{/if}/> {$LANG_SEARCH_WITH_ANONYMS}</label>
	                     	</div>
	                     	<div class="search_item">
	                     		<label>{$LANG_SEARCH_STATUS}:</label>
	                     		<select id="search_status">
	                     			<option value="-1">- - - {$LANG_SEARCH_ANY_STATUS} - - -</option>
	                     			<option value="1" {if $args.search_status == 1}selected{/if}>{$LANG_STATUS_ACTIVE}</option>
	                     			<option value="2" {if $args.search_status == 2}selected{/if}>{$LANG_STATUS_SPAM}</option>
	                     		</select>
	                     	</div>
	                     	
	                     	{assign var=field_title value=$LANG_SEARCH_DATE_ADDED}
	                     	{assign var=search_mode value=$mode}
	                     	{assign var=date_var_name value="date_added"}
	                     	{assign var=single_date_var_value value=$date_added}
	                     	{assign var=single_date_var_value_tmstmp value=$date_added_tmstmp}
	                     	{assign var=from_date_var_value value=$from_date_added}
	                     	{assign var=from_date_var_value_tmstmp value=$from_date_added_tmstmp}
	                     	{assign var=to_date_var_value value=$to_date_added}
	                     	{assign var=to_date_var_value_tmstmp value=$to_date_added_tmstmp}
	                     	{include file="content_fields/common_date_range_search.tpl"}
                     	</div>
                     	<div class="clear_float"></div>
                     	<div class="search_item_button">
	                    	<input type="submit" class="button search_button" id="process_search" value="{$LANG_BUTTON_SEARCH_REVIEWS}">
	                    </div>
                     </form>
                     
                     <div class="search_results_title">
                     	{$LANG_SEARCH_REVIEWS_RESULT_1} ({$reviews_count} {$LANG_SEARCH_REVIEWS_RESULT_2}):
                     </div>

                     {if $reviews|@count > 0}
                     <form id="reviews_form" action="" method="post" onSubmit="submit_reviews_form();">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th width="1"><input type="checkbox"></th>
                         <th>{$LANG_USERS_LOGIN_TH}</th>
                         <th>{$LANG_REVIEW_STATUS_TH}</th>
                         <th>{$LANG_REVIEW_BODY_TH}</th>
                         <th>{asc_desc_insert base_url=$search_url orderby='r.date_added' orderby_query=$orderby direction=$direction title=$LANG_PLACEMENT_DATE_TH}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$reviews item=review}
						{assign var=user value=$review->getUser()}
						{assign var=object value=$review->getObject()}
						{assign var=rating value=$review->setRating()}
						{assign var="review_id" value=$review->id}
						{assign var="review_object_id" value=$review->object_id}
                       <tr>
                         <td>
                    	  	<input type="checkbox" name="cb_{$review->id}" value="{$review->id}">
                    	 </td>
                         <td>
                         	{if $review->user_id}
	                         	{assign var="review_user_id" value=$review->user->id}
								{if $review_user_id != 1 && $content_access_obj->isPermission('Manage users')}
									<a href="{$VH->site_url("admin/users/view/$review_user_id")}">{$review->user->login}</a>
								{else}
									{$review->user->login}
								{/if}
							{else}
								{$LANG_ANONYM}: {$review->anonym_name} ({$review->ip})
							{/if}
                         </td>
                         <td>
                         	{if $review->status == 1}<span class="status_active">{$LANG_STATUS_ACTIVE}</span>{/if}
							{if $review->status == 2}<span class="status_spam">{$LANG_STATUS_SPAM}</span>{/if}
                         </td>
                         <td>
                         	{$review->review}
                         </td>
                         <td>
                            {$review->date_added|date_format:"%D %T"}&nbsp;
                         </td>
                         <td>
                         	<nobr>
                         	<a href="{$VH->site_url("admin/reviews/edit/$review_id")}"><img src="{$public_path}/images/buttons/page_edit.png" /></a>
                         	{if $review->object->isObject()}
                         	<a href="{$review->object->getObjectReviewsUrl()}" title="{$LANG_REVIEWS_VIEW_ALL_REVIEWS}"><img src="{$public_path}/images/buttons/page_copy.png" /></a>
                         	{/if}
                         	</nobr>
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     {$LANG_WITH_SELECTED}:
	                 <select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_reviews_form(); this.form.submit()">
	                 	<option value="">{$LANG_CHOOSE_ACTION}</option>
	                 	<option value="delete">{$LANG_BUTTON_DELETE_REVIEWS}</option>
	                 	<option value="spam">{$LANG_BUTTON_SPAM_REVIEWS}</option>
	                 	<option value="active">{$LANG_BUTTON_ACTIVE_REVIEWS}</option>
	                 </select>
                     {$paginator}
                     </form>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}