<?php /* Smarty version 2.6.26, created on 2012-02-06 04:25:24
         compiled from backend/categories/admin_categories_in_listings.tpl */ ?>
%OBRACKET%
	"data" : %OBRACKET%
		"title" : "<?php echo $this->_tpl_vars['VH']->_utf8_encode("%NAME_WITHOUT_QUOTES%"); ?>
&nbsp;&nbsp;&nbsp;<a href=\"javascript: void(0);\" name=\"<?php echo $this->_tpl_vars['VH']->_utf8_encode("%NAME_WITHOUT_QUOTES%"); ?>
\" class=\"tree_option\" onClick=\"addCategory(this)\"><?php echo $this->_tpl_vars['VH']->_utf8_encode($this->_tpl_vars['LANG_BUTTON_ADD_TO_LIST']); ?>
<\/a>"
	%CBRACKET%,
	"attr" : %OBRACKET% 
		"id" : "category_in_listing_%ID%"
	%CBRACKET%
	%ISCHILDRENLABEL%
%CBRACKET%,