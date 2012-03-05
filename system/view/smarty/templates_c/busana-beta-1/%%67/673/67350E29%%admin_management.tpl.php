<?php /* Smarty version 2.6.26, created on 2012-02-06 03:26:13
         compiled from backend/categories/admin_management.tpl */ ?>
<?php if (! $this->_tpl_vars['type_id']): ?>
%OBRACKET%
	"data" : %OBRACKET%
		"title" : "<?php echo $this->_tpl_vars['VH']->_utf8_encode("%NAME_WITHOUT_QUOTES%"); ?>
&nbsp;&nbsp;&nbsp;(%SEONAME%)&nbsp;&nbsp;&nbsp;<a href=<?php echo $this->_tpl_vars['VH']->site_url("admin/categories/create_child/%ID%"); ?>
 class=\"tree_option\" onClick=\"document.location.href=this.href\"><?php echo $this->_tpl_vars['VH']->_utf8_encode($this->_tpl_vars['LANG_CATEGORY_CREATE_CHILD']); ?>
<\/a>&nbsp;&nbsp;&nbsp;<a href=<?php echo $this->_tpl_vars['VH']->site_url("admin/categories/edit/%ID%"); ?>
 class=\"tree_option\" onClick=\"document.location.href=this.href\"><?php echo $this->_tpl_vars['VH']->_utf8_encode($this->_tpl_vars['LANG_CATEGORY_EDIT']); ?>
<\/a>&nbsp;&nbsp;&nbsp;<a href=<?php echo $this->_tpl_vars['VH']->site_url("admin/categories/delete/%ID%"); ?>
 class=\"tree_option\" onClick=\"document.location.href=this.href\"><?php echo $this->_tpl_vars['VH']->_utf8_encode($this->_tpl_vars['LANG_CATEGORY_DELETE']); ?>
<\/a>"
	%CBRACKET%,
	"attr" : %OBRACKET% 
		"id" : "category_%ID%"
	%CBRACKET%
	%ISCHILDRENLABEL%
%CBRACKET%,<?php else: ?>
%OBRACKET%
	"data" : %OBRACKET%
		"title" : "<?php echo $this->_tpl_vars['VH']->_utf8_encode("%NAME_WITHOUT_QUOTES%"); ?>
&nbsp;&nbsp;&nbsp;(%SEONAME%)&nbsp;&nbsp;&nbsp;<a href=<?php echo $this->_tpl_vars['VH']->site_url("admin/categories/by_type/".($this->_tpl_vars['type_id'])."/create_child/%ID%"); ?>
 class=\"tree_option\" onClick=\"document.location.href=this.href\"><?php echo $this->_tpl_vars['VH']->_utf8_encode($this->_tpl_vars['LANG_CATEGORY_CREATE_CHILD']); ?>
<\/a>&nbsp;&nbsp;&nbsp;<a href=<?php echo $this->_tpl_vars['VH']->site_url("admin/categories/by_type/".($this->_tpl_vars['type_id'])."/edit/%ID%"); ?>
 class=\"tree_option\" onClick=\"document.location.href=this.href\"><?php echo $this->_tpl_vars['VH']->_utf8_encode($this->_tpl_vars['LANG_CATEGORY_EDIT']); ?>
<\/a>&nbsp;&nbsp;&nbsp;<a href=<?php echo $this->_tpl_vars['VH']->site_url("admin/categories/by_type/".($this->_tpl_vars['type_id'])."/delete/%ID%"); ?>
 class=\"tree_option\" onClick=\"document.location.href=this.href\"><?php echo $this->_tpl_vars['VH']->_utf8_encode($this->_tpl_vars['LANG_CATEGORY_DELETE']); ?>
<\/a>"
	%CBRACKET%,
	"attr" : %OBRACKET% 
		"id" : "category_%ID%"
	%CBRACKET%
	%ISCHILDRENLABEL%
%CBRACKET%,<?php endif; ?>