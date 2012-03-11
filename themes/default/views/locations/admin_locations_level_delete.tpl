{include file="admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_DELETE_LOC_LEVEL} "{$level.name}"</h3>
                     <form action="" method="post">
                     <input type=hidden name="id" value="{$level.id}">
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_DELETE_LOC_LEVEL_QUEST}
                          </div>
                          <div class="admin_option_description">
                               &nbsp;
                          </div>
                          <input type=submit name="yes" value="{$LANG_YES}">&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type=submit name="no" value="{$LANG_NO}">
                     </div>
                </div>

{include file="admin_footer.tpl"}