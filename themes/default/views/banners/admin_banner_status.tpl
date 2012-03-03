{include file="backend/admin_header.tpl"}

                <div class="content">
					<h3>{$LANG_BANNERS_CHANGE_STATUS_BANNER_TITLE}</h3>
                     
					<div class="admin_top_menu_cell">
	                    <a href="{$VH->site_url("admin/banners/create")}" title="{$LANG_CREATE_BANNER}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
	                    <a href="{$VH->site_url("admin/banners/create")}">{$LANG_CREATE_BANNER}</a>
	                </div>
	                <div class="admin_top_menu_cell">
	                    <a href="{$VH->site_url("admin/banners/edit/$banner_id")}" title="{$LANG_EDIT_BANNER}"><img src="{$public_path}/images/buttons/page_edit.png" /></a>
	                    <a href="{$VH->site_url("admin/banners/edit/$banner_id")}">{$LANG_EDIT_BANNER}</a>
	                </div>
	                <div class="admin_top_menu_cell">
	                    <a href="{$VH->site_url("admin/banners/delete/$banner_id")}" title="{$LANG_DELETE_BANNER}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
	                    <a href="{$VH->site_url("admin/banners/delete/$banner_id")}">{$LANG_DELETE_BANNER}</a>
	                </div>
					<div class="clear_float"></div>
                     
                     <form action="" method="post">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th width="1">&nbsp;</th>
                         <th>{$LANG_STATUS_TH}</th>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" id="status_1" name="status" value="1" {if $banner->status == 1}checked{/if}>
                         </td>
                         <td>
                             <label for="status_1">{$LANG_STATUS_ACTIVE}</label>
                         </td>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" id="status_2" name="status" value="2" {if $banner->status == 2}checked{/if}>
                         </td>
                         <td>
                             <label for="status_2">{$LANG_STATUS_BLOCKED}</label>
                         </td>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" id="status_3" name="status" value="3" {if $banner->status == 3}checked{/if}>
                         </td>
                         <td>
                             <label for="status_3">{$LANG_STATUS_SUSPENDED}</label>
                         </td>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" id="status_4" name="status" value="4" {if $banner->status == 4}checked{/if}>
                         </td>
                         <td>
                             <label for="status_4">{$LANG_STATUS_NOTPAID}</label>
                         </td>
                       </tr>
                     </table>
                     <br/>
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}