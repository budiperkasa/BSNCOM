<?php /* Smarty version 2.6.26, created on 2012-02-10 06:16:05
         compiled from listings/admin_listing_status.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                <div class="content">
					<h3><?php echo $this->_tpl_vars['LANG_LISTING_STATUS']; ?>
 "<?php echo $this->_tpl_vars['listing']->title(); ?>
"</h3>
                     
					<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "listings/admin_listing_options_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                     
                     <form action="" method="post">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th width="1">&nbsp;</th>
                         <th><?php echo $this->_tpl_vars['LANG_STATUS_TH']; ?>
</th>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" id="status_1" name="status" value="1" <?php if ($this->_tpl_vars['listing']->status == 1): ?>checked<?php endif; ?>>
                         </td>
                         <td>
                             <label for="status_1"><?php echo $this->_tpl_vars['LANG_STATUS_ACTIVE']; ?>
</label>
                         </td>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" id="status_2" name="status" value="2" <?php if ($this->_tpl_vars['listing']->status == 2): ?>checked<?php endif; ?>>
                         </td>
                         <td>
                             <label for="status_2"><?php echo $this->_tpl_vars['LANG_STATUS_BLOCKED']; ?>
</label>
                         </td>
                       </tr>

                       <?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage all listings')): ?>
                       <tr>
                         <td>
                             <input type="radio" id="status_3" name="status" value="3" <?php if ($this->_tpl_vars['listing']->status == 3): ?>checked<?php endif; ?>>
                         </td>
                         <td>
                             <label for="status_3"><?php echo $this->_tpl_vars['LANG_STATUS_SUSPENDED']; ?>
</label>
                         </td>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" id="status_4" name="status" value="4" <?php if ($this->_tpl_vars['listing']->status == 4): ?>checked<?php endif; ?>>
                         </td>
                         <td>
                             <label for="status_4"><?php echo $this->_tpl_vars['LANG_STATUS_UNAPPROVED']; ?>
</label>
                         </td>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" id="status_5" name="status" value="5" <?php if ($this->_tpl_vars['listing']->status == 5): ?>checked<?php endif; ?>>
                         </td>
                         <td>
                             <label for="status_5"><?php echo $this->_tpl_vars['LANG_STATUS_NOTPAID']; ?>
</label>
                         </td>
                       </tr>
                       <?php endif; ?>
                     </table>

                     <input class="button save_button" type=submit name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
">
                     </form>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>