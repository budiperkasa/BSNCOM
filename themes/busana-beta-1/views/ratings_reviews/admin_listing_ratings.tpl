{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
					var action_cmd;

					function submit_ratings_form()
	            	{ldelim}
	              		$("#ratings_form").attr("action", '{$VH->site_url('admin/ratings/')}' + action_cmd + '/');
	               		return true;
	            	{rdelim}
                </script>

                <div class="content">
                    <h3>{$LANG_ADMIN_RATINGS_TITLE} "{$listing->title()}"</h3>

                    {include file="listings/admin_listing_options_menu.tpl"}
                    
                    <div>{$LANG_AVERAGE}: {$avg_rating->avg_value}</div>
                    {$avg_rating->view()}
                    <div class="clear_float"></div>

                    <form id="ratings_form" action="" method="post" onSubmit="submit_ratings_form();">
                    <input type="hidden" name="listing_id" value="{$listing->id}">
                    <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                    	<tr>
                    		<th width="1"><input type="checkbox"></th>
                    		<th>{$LANG_RATING_TH}</th>
                    		<th>{$LANG_USER_TH}</th>
                    		<th>{$LANG_IP_TH}</th>
                    		<th>{$LANG_PLACEMENT_DATE_TH}</th>
                    	</tr>
                    	{foreach from=$ratings item=rating}
                    	<tr>
                    	  <td>
                    	  	<input type="checkbox" name="cb_{$rating->id}" value="{$rating->id}">
                    	  </td>
                    	  <td>
                    	  	{$rating->view()}
                    	  </td>
                    	  <td>
                    	  	{if $rating->user_id}
                    	  		{assign var="rating_user_id" value=$rating->user_id}
                    	  		{if $rating_user_id != 1 && $content_access_obj->isPermission('Manage users')}
									<a href="{$VH->site_url("admin/users/view/$rating_user_id")}">{$rating->user->login}</a>
								{else}
									{$rating->user->login}
								{/if}
                    	  	{else}
                    	  		{$LANG_ANONYM}&nbsp;
                    	  	{/if}
                    	  </td>
                    	  <td>
                    	  	{$rating->ip}&nbsp;
                    	  </td>
                    	  <td>
                    	  	{$rating->date_added|date_format:"%D %T"}&nbsp;
                    	  </td>
                    	</tr>
                    	{/foreach}
                    	</table>

                    	{$LANG_WITH_SELECTED}:
		                 <select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_ratings_form(); this.form.submit()">
		                 	<option value="">{$LANG_CHOOSE_ACTION}</option>
		                 	<option value="delete">{$LANG_BUTTON_DELETE_RATINGS}</option>
		                 </select>
                    	</form>
                    </div>
                </div>

{include file="backend/admin_footer.tpl"}