{if !$type_id}
%OBRACKET%
	"data" : %OBRACKET%
		"title" : "{$VH->_utf8_encode("%NAME_WITHOUT_QUOTES%")}&nbsp;&nbsp;&nbsp;(%SEONAME%)&nbsp;&nbsp;&nbsp;<a href={$VH->site_url("admin/categories/create_child/%ID%")} class=\"tree_option\" onClick=\"document.location.href=this.href\">{$VH->_utf8_encode($LANG_CATEGORY_CREATE_CHILD)}<\/a>&nbsp;&nbsp;&nbsp;<a href={$VH->site_url("admin/categories/edit/%ID%")} class=\"tree_option\" onClick=\"document.location.href=this.href\">{$VH->_utf8_encode($LANG_CATEGORY_EDIT)}<\/a>&nbsp;&nbsp;&nbsp;<a href={$VH->site_url("admin/categories/delete/%ID%")} class=\"tree_option\" onClick=\"document.location.href=this.href\">{$VH->_utf8_encode($LANG_CATEGORY_DELETE)}<\/a>"
	%CBRACKET%,
	"attr" : %OBRACKET% 
		"id" : "category_%ID%"
	%CBRACKET%
	%ISCHILDRENLABEL%
%CBRACKET%,{else}
%OBRACKET%
	"data" : %OBRACKET%
		"title" : "{$VH->_utf8_encode("%NAME_WITHOUT_QUOTES%")}&nbsp;&nbsp;&nbsp;(%SEONAME%)&nbsp;&nbsp;&nbsp;<a href={$VH->site_url("admin/categories/by_type/$type_id/create_child/%ID%")} class=\"tree_option\" onClick=\"document.location.href=this.href\">{$VH->_utf8_encode($LANG_CATEGORY_CREATE_CHILD)}<\/a>&nbsp;&nbsp;&nbsp;<a href={$VH->site_url("admin/categories/by_type/$type_id/edit/%ID%")} class=\"tree_option\" onClick=\"document.location.href=this.href\">{$VH->_utf8_encode($LANG_CATEGORY_EDIT)}<\/a>&nbsp;&nbsp;&nbsp;<a href={$VH->site_url("admin/categories/by_type/$type_id/delete/%ID%")} class=\"tree_option\" onClick=\"document.location.href=this.href\">{$VH->_utf8_encode($LANG_CATEGORY_DELETE)}<\/a>"
	%CBRACKET%,
	"attr" : %OBRACKET% 
		"id" : "category_%ID%"
	%CBRACKET%
	%ISCHILDRENLABEL%
%CBRACKET%,{/if}