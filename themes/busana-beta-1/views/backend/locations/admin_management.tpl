{if $curr_levels_count<$loc_levels_count}
%OBRACKET%
	"data" : %OBRACKET%
		"title" : "<span %HIGHLIGHT%>{$VH->_utf8_encode("%NAME_WITHOUT_QUOTES%")}<\/span>&nbsp;&nbsp;&nbsp;(%SEONAME%)&nbsp;&nbsp;&nbsp;<a href={$VH->site_url("admin/locations/create_child/%ID%")} class=\"tree_option\" onClick=\"document.location.href=this.href\">{$VH->_utf8_encode($LANG_LOCATION_CREATE_CHILD)}<\/a>&nbsp;&nbsp;&nbsp;<a href={$VH->site_url("admin/locations/edit/%ID%")} class=\"tree_option\" onClick=\"document.location.href=this.href\">{$VH->_utf8_encode($LANG_LOCATION_EDIT)}<\/a>&nbsp;&nbsp;&nbsp;<a href={$VH->site_url("admin/locations/delete/%ID%")} class=\"tree_option\" onClick=\"document.location.href=this.href\">{$VH->_utf8_encode($LANG_LOCATION_DELETE)}<\/a>&nbsp;&nbsp;&nbsp;<a href={$VH->site_url("admin/locations/label/%ID%")} class=\"tree_option\" onClick=\"document.location.href=this.href\">{$VH->_utf8_encode($LANG_LOCATION_LABEL)}<\/a>"
	%CBRACKET%,
	"attr" : %OBRACKET% 
		"id" : "location_%ID%"
	%CBRACKET%
	%ISCHILDRENLABEL%
%CBRACKET%,{else}
%OBRACKET%
	"data" : %OBRACKET%
		"title" : "<span %HIGHLIGHT%>{$VH->_utf8_encode("%NAME_WITHOUT_QUOTES%")}<\/span>&nbsp;&nbsp;&nbsp;(%SEONAME%)&nbsp;&nbsp;&nbsp;<a href={$VH->site_url("admin/locations/edit/%ID%")} class=\"tree_option\" onClick=\"document.location.href=this.href\">{$VH->_utf8_encode($LANG_LOCATION_EDIT)}<\/a>&nbsp;&nbsp;&nbsp;<a href={$VH->site_url("admin/locations/delete/%ID%")} class=\"tree_option\" onClick=\"document.location.href=this.href\">{$VH->_utf8_encode($LANG_LOCATION_DELETE)}<\/a>&nbsp;&nbsp;&nbsp;<a href={$VH->site_url("admin/locations/label/%ID%")} class=\"tree_option\" onClick=\"document.location.href=this.href\">{$VH->_utf8_encode($LANG_LOCATION_LABEL)}<\/a>"
	%CBRACKET%,
	"attr" : %OBRACKET% 
		"id" : "location_%ID%"
	%CBRACKET%
	%ISCHILDRENLABEL%
%CBRACKET%,{/if}