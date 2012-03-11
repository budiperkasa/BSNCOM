{include file="backend/admin_header.tpl"}
<!-- 

There are 4 arguments, passed to this template
$options - array of arguments those will be called back in the controller, pass option values in array keys, but names in array values
$hidden - array of hidden arguments those will be called back in the controller
$heading - heading of the template
$question - question message

 -->

                <div class="content">
                     <h3>{$heading}</h3>
                     <form action="" method="post">
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$question}
                          </div>
                          <div class="admin_option_description">
                          	{if $options|@count > 1}
                          		<ul>
                          		{foreach from=$options item=option key=key}
                          			{if $option != '' && $option != null}
                          			<li>{$option}</li>
                          			{else}
                          			<li>(No Title)</li>
                          			{/if}
                          			<input type="hidden" name="options[]" value="{$key}" />
                          		{/foreach}
                          		</ul>
                          	{elseif $options|@count == 1}
                          		{foreach from=$options item=option key=key}
	                          		{if $option != '' && $option != null}
	                          			{$option}
	                          		{else}
	                          			(No Title)
	                          		{/if}
	                          		<input type="hidden" name="options[]" value="{$key}" />
                          		{/foreach}
                          	{else}
                          		&nbsp;
                          	{/if}
                          </div>
                          <br />
                          <br />

                          {foreach from=$hidden item=option key=key}
                          <input type="hidden" name="{$key}" value="{$option}" />
                          {/foreach}

                          <input type=submit name="yes" value="{$LANG_YES}">&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type=submit name="no" value="{$LANG_NO}">
                     </div>
                </div>

{include file="backend/admin_footer.tpl"}