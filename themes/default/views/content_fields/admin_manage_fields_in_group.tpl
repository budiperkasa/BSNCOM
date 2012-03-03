{include file="backend/admin_header.tpl"}

<script type="text/javascript">
var ajax_save_fields_list_url = '{$VH->site_url("admin/fields/groups/manage_fields/$group_id/save/")}';

$(document).ready(function(){ldelim}
		$(".sortable").sortable({ldelim}
        		connectWith: ".sortable",
        		items: 'li.drag',
        		placeholder: 'drop_highlight',
        		forcePlaceholderSize: true
		{rdelim});

		$("ul, li").disableSelection();	

        $("#save").click(function() {ldelim}
        		ajax_loader_show("{$LANG_FIELDS_SAVE_MSG}");
                $.post(ajax_save_fields_list_url, {ldelim}serialized_list: $('#serialized').sortable('serialize'){rdelim}, function(data) {ldelim}
                	ajax_loader_hide();
                {rdelim});
        {rdelim});
{rdelim});
</script> 

                <div class="content">
                     <h3>{$LANG_MANAGE_CONTENT_FIELDS_GROUP} "{$group_name}"</h3>
                     <h4>{$LANG_DONT_FORGET_CHANGES}</h4>
                     
                     <div class="clear_float"></div>
                     <ul class="sortable" style="float: left;">
                       <li class="drop_title">
                       		{$LANG_ALL_CONTENT_FIELDS}
                       </li>
                       {foreach from=$free_fields item=field}
                       {assign var=field_id value=$field.id}
                       <li id="field_{$field.id}" class="drag">
                       	 	{$field.name}
                       	 	<div class="field_type">{$field.type_name}</div>
							{if $field.configuration_page}
							<div class="field_options_link"><a href="{$VH->site_url("admin/fields/configure/$field_id")}">{$LANG_CONFIGURE_FIELD_OPTION}</a>&nbsp;&nbsp;
							<a href="{$VH->site_url("admin/fields/edit/$field_id")}">{$LANG_EDIT_FIELD_OPTION}</a></div>
							{/if}
                       </li>
                       {/foreach}
                     </ul>
                     <ul class="sortable" id="serialized" style="float: left;">
                       <li class="drop_title">
                       		{$LANG_FIELDS_OF_GROUP}
                       </li>
                       {foreach from=$fields_of_group item=field}
                       {assign var=field_id value=$field.id}
                       <li id="field_{$field.id}" class="drag">
                       	 	{$field.name}
                       	 	<div class="field_type">{$field.type_name}</div>
                       	 	{if $field.configuration_page}
							<div class="field_options_link"><a href="{$VH->site_url("admin/fields/configure/$field_id")}">{$LANG_CONFIGURE_FIELD_OPTION}</a>&nbsp;&nbsp;
							<a href="{$VH->site_url("admin/fields/edit/$field_id")}">{$LANG_EDIT_FIELD_OPTION}</a></div>
							{/if}
                       </li>
                       {/foreach}
                     </ul>

                     <div class="clear_float"></div>
                     
                     <br />
                     <br />
                     <input class="button save_button" type=button id="save" name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}" />
                </div>

{include file="backend/admin_footer.tpl"}