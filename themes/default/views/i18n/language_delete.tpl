{include file="admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_DELETE} "{$language->name}"</h3>
                     <form action="" method="post">
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_DELETE_QUEST}
                          </div>
                          <div class="admin_option_description">
                               &nbsp;
                          </div>
                          <input type=submit name="yes" value="{$LANG_YES}">&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type=submit name="no" value="{$LANG_NO}">
                     </div>
                </div>

{include file="admin_footer.tpl"}