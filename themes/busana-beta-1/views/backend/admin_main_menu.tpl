
<!-- MAIN MENU -->
			<script language="JavaScript" type="text/javascript" src="{$smarty_obj->getFileInTheme('js/admin_menu.js')}"></script>
			<td width="230px">
				<div class="main_menu">
                     <div id="extra">
                       <h2><span>{$LANG_MAIN_MENU}</span></h2>
                     </div>
                     <div class="menu_content">
                     	<div id="ex1">
                     		<ul id="browser" class="filetree">
                     			{$main_menu_list}
                     		</ul>
                     	</div>
                     	
                     	<script language="javascript" type="text/javascript">
                     		<!-- smarty 'insert.route.php' function -->
                     		{insert name="route"}

                         	 var sinonims = new Array();
                         	 {$sinonims_sinonim_input}
                         	 var urls = new Array();
                         	 {$sinonims_url_input}
                     	</script>

                     </div>
                </div>
             </td>
<!-- /MAIN MENU -->