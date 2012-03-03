<?php

$language['LANG_SETTINGS_TYPE_STRUCTURE'] = "Listings types mode";
$language['LANG_ENABLE_CATEGORIES_SEARCH'] = "Enable global categories search list";
$language['LANG_SETTINGS_TYPE_STRUCTURE_SINGLE_NOTE'] = "Single-type mode available when only one listings type exists.";
$language['LANG_SETTINGS_TYPE_STRUCTURE_MULTI'] = "Multi-types mode (Different types of listings allow users to create <a href='http://www.salephpscripts.com/manual/types_levels/' target=_blank>different types of content</a>. Also you may set different categories lists and search options for each type)";
$language['LANG_SETTINGS_TYPE_STRUCTURE_SINGLE'] = "Single-type mode (Users will be able to create listings of only one general type. Best way for pure classifieds site)";
$language['LANG_TYPES_SINGLE_MODE_ERROR'] = "For single-type mode there must be only one listings type in the system! This type must have global based settings (categories and search must be set to global)";
$language['LANG_TYPES_SINGLE_MODE_ERROR_HELP'] = "You may manage listings types mode on system settings page.";
$language['LANG_BUTTON_CLEAR_CACHE'] = "Clear cache";
$language['LANG_SETTINGS_CACHED_CLEARED_SUCCESS'] = "Cache was cleared successfully!";
$language['LANG_BUTTON_SYNCHRONIZE_USERS_CONTENT'] = "Synchronize users content";
$language['LANG_SYNCHRONIZE_USERS_CONTENT_SUCCESS'] = "Users content files were synchronized successfully!";

$language['LANG_SETTINGS_LISTINGS_CREATION_MODE'] = "Memberships packages";
$language['LANG_SETTINGS_LISTINGS_CREATION_MODE_STANDALONE'] = "disabled - users singly create and pay for listings";
$language['LANG_SETTINGS_LISTINGS_CREATION_MODE_MEMBERSHIPS'] = "enabled - users pay for membership packages and create listings under this packages";
$language['LANG_SETTINGS_LISTINGS_CREATION_MODE_BOTH'] = "both modes - users may pay for membership packages and may singly create and pay for listings";

$language['LANG_MISCELLANEOUS_SETTINGS_MENU'] = "Miscellaneous";

$language['LANG_FRONTEND_SETTINGS_ALONE_PAGES'] = "Listings view for pages with mixed types";
$language['LANG_LISTING_LEVELS_VISIBLE'] = "Visible levels";
$language['LANG_FRONTEND_SETTINGS_PAGE'] = "Listings view for";
$language['LANG_QUICK_LIST_PAGE_TH'] = "Quick list page";
$language['LANG_FRONTEND_SETTING_SEMITABLE'] = "semitable";
$language['LANG_FRONTEND_SETTING_SHORT'] = "short";
$language['LANG_FRONTEND_SETTING_FULL'] = "full";
$language['LANG_SEARCH_RANDOM'] = "Random";
$language['LANG_SEARCH_REVIEWS_COUNT'] = "Reviews/comments number";
$language['LANG_SEARCH_LAST_REVIEW_DATE'] = "Last review/comment date-time";

/**
 * Titles settings
 */
$language['LANG_TITLES_TEMPLATES_MENU'] = "Titles templates";
$language['LANG_TITLES_TEMPLATES_TITLE'] = "Titles templates";
$language['LANG_TEMPLATES'] = "Templates";
$language['LANG_TITLES_OF_TYPE'] = "Listing titles of type";
$language['LANG_TITLES_OF_LEVEL'] = "of level";
$language['LANG_TITLES_TEMPLATE_SAVE_SUCCESS'] = "Titles templates were saved successfully!";
$language['LANG_TITLES_TEMPLATES_DESCR'] = "Listings may have titles other than entered by user, configure titles using provided templates:
<br />
%CUSTOM_TITLE% - title entered by user;<br />
%LISTING_ID% - unique listing ID;<br />
%USER_LOGIN% - listing author's login;<br />
%USER_EMAIL% - listing author's email;<br />
%FIELD_field seo name% - value of additional content field;<br />
%CATEGORY_1, 2, 3, ....% - categories by number assigned with this listing;<br />
%CATEGORIES(glue)% - all categories assigned with this listing, concatenated with 'glue';<br />
%LOCATION_1, 2, 3, ....% - locations by number assigned with this listing;<br />
%LOCATIONS(glue)% - all locations assigned with this listing, concatenated with 'glue'
<div class='px10'></div>
For example with such template %CUSTOM_TITLE% - %FIELD_PRICE% | %CATEGORIES( - )% in %LOCATION_1%<br />
the result may be:<br />
<i>BigMc Cafe - $ 10.00 | fast food - hot dogs in Chicago</i>";

$language['LANG_PATH_TO_TERMS_CONDITIONS'] = "Path to terms and conditions";
$language['LANG_PATH_TO_TERMS_CONDITIONS_DESCR'] = "When entered - checkbox 'I agree with terms and conditions' will appear on registration page. Example 'node/terms_conditions/'";
?>