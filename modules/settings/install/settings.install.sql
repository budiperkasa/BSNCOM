CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `value` text,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `site_settings` VALUES (1, 'website_title', '');
INSERT INTO `site_settings` VALUES (2, 'description', '');
INSERT INTO `site_settings` VALUES (3, 'keywords', '');
INSERT INTO `site_settings` VALUES (4, 'company_details', '');
INSERT INTO `site_settings` VALUES (5, 'signature_in_emails', '');

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `value` text,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `system_settings` VALUES (1, 'site_logo_file', '');
INSERT INTO `system_settings` VALUES (2, 'design_theme', 'default');
INSERT INTO `system_settings` VALUES (3, 'default_language', 'en');
INSERT INTO `system_settings` VALUES (4, 'google_analytics_account_id', '');
INSERT INTO `system_settings` VALUES (5, 'google_analytics_profile_id', '');
INSERT INTO `system_settings` VALUES (6, 'google_analytics_email', '');
INSERT INTO `system_settings` VALUES (7, 'google_analytics_password', '');
INSERT INTO `system_settings` VALUES (8, 'youtube_key', '');
INSERT INTO `system_settings` VALUES (9, 'youtube_username', '');
INSERT INTO `system_settings` VALUES (10, 'youtube_password', '');
INSERT INTO `system_settings` VALUES (11, 'youtube_product_name', '');
INSERT INTO `system_settings` VALUES (12, 'website_email', '');
INSERT INTO `system_settings` VALUES (13, 'auto_blocker_timestamp', '');
INSERT INTO `system_settings` VALUES (14, 'global_what_search', '1');
INSERT INTO `system_settings` VALUES (15, 'global_where_search', '1');
INSERT INTO `system_settings` VALUES (16, 'global_categories_search', '1');
INSERT INTO `system_settings` VALUES (17, 'single_type_structure', '0');
INSERT INTO `system_settings` VALUES (18, 'categories_block_type', 'categories-bar');
INSERT INTO `system_settings` VALUES (19, 'W2D_VERSION', '3.1.0');
INSERT INTO `system_settings` VALUES (20, 'search_in_raduis_measure', 'miles');
INSERT INTO `system_settings` VALUES (21, 'anonym_rates_reviews', '0');
INSERT INTO `system_settings` VALUES (22, 'enable_contactus_page', '1');
INSERT INTO `system_settings` VALUES (23, 'predefined_locations_mode', 'only');
INSERT INTO `system_settings` VALUES (24, 'path_to_terms_and_conditions', '');
INSERT INTO `system_settings` VALUES (25, 'geocoded_locations_mode_districts', 1);
INSERT INTO `system_settings` VALUES (26, 'geocoded_locations_mode_provinces', '1');
INSERT INTO `system_settings` VALUES (27, 'mollom_public_key', '');
INSERT INTO `system_settings` VALUES (28, 'mollom_private_key', '');