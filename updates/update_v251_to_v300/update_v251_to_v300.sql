ALTER TABLE `videos` CHANGE `status` `status` VARCHAR( 30 ) NOT NULL;
ALTER TABLE `videos` CHANGE `video_id` `video_code` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `videos` ADD `error_code` VARCHAR( 255 ) NOT NULL AFTER `status`;


UPDATE `system_settings` SET value='rating' WHERE name='def_listings_order' AND value='r.rating';


INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Listing change level', 'Notification sends to listing owner when his listing changes level.\r\nTokens: LISTING_ID, LISTING_TITLE, RECIPIENT_NAME, NEW_LISTING_LEVEL, OLD_LISTING_LEVEL, RECIPIENT_EMAIL, REVIEW_BODY', 'Level of your listing ''%LISTING_TITLE%'' was changed', 'Dear %RECIPIENT_NAME%,\r\n\r\nlevel of your listing "%LISTING_TITLE%" was changed from "%OLD_LISTING_LEVEL%" to "%NEW_LISTING_LEVEL%".\r\n\r\nRegards,\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');
INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Category suggestion', 'Notification sends to site manager when user suggests new category.\r\nTokens: SUGGESTED_CATEGORY, SENDER_NAME, SENDER_EMAIL, RECIPIENT_EMAIL', 'New category was suggested', 'Dear website manager,\r\n\r\nuser %SENDER_NAME% suggested new category: %SUGGESTED_CATEGORY%\r\Now you may approve and add this category into the list on categories management page.\r\nYou may notify user on this email: %SENDER_EMAIL%\r\n\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');
INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Listing claim', 'Notification sends to listing owner when user claims the listing.\r\nTokens: LISTING_TITLE, SENDER_NAME, SENDER_EMAIL, RECIPIENT_NAME, RECIPIENT_EMAIL', 'Listing was claimed', 'Dear %RECIPIENT_NAME%,\r\n\r\nuser %SENDER_NAME% claimed your listing: %LISTING_TITLE%\r\nNow you may approve or decline this claim.\r\nYou may notify user by this email: %SENDER_EMAIL%\r\n\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');
INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Listing claim approved', 'Notification sends to user when his claim on listing was approved.\r\nTokens: LISTING_TITLE, RECIPIENT_NAME, RECIPIENT_EMAIL', 'Claim was approved', 'Dear %RECIPIENT_NAME%,\r\n\r\nyour claim on listing: %LISTING_TITLE%\r\nwas approved\r\n\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');
INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Listing claim declined', 'Notification sends to user when his claim on listing was declined.\r\nTokens: LISTING_TITLE, RECIPIENT_NAME, RECIPIENT_EMAIL', 'Claim was declined', 'Dear %RECIPIENT_NAME%,\r\n\r\nyour claim on listing: %LISTING_TITLE%\r\nwas declined\r\n\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');
INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Reply on your comment', 'Notification send to user when somebody replies on his comment.\r\nTokens: LISTING_TITLE, LISTING_URL, RECIPIENT_NAME, RECIPIENT_EMAIL, REVIEW_BODY', 'New reply on your comment for listing ''%LISTING_TITLE%''', 'Dear %RECIPIENT_NAME%,\r\n\r\ncheck new reply on your comment for listing ''%LISTING_TITLE%''\r\n%LISTING_URL%\r\n\r\nComment body:\r\n%REVIEW_BODY%\r\n\r\nRegards,\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');


ALTER TABLE `listings_in_locations` ADD `predefined_location_id` INT NOT NULL AFTER `geocoded_city`;
ALTER TABLE `listings_in_locations` ADD `use_predefined_locations` BOOLEAN NOT NULL AFTER `predefined_location_id`;
ALTER TABLE `listings_in_locations` DROP `geocoded_country`, DROP `geocoded_state`, DROP `geocoded_city`;

ALTER TABLE `listings_in_locations` CHANGE `map_coords_1` `map_coords_1` FLOAT( 10, 6 ) NOT NULL , CHANGE `map_coords_2` `map_coords_2` FLOAT( 10, 6 ) NOT NULL;

ALTER TABLE `listings` ADD `listing_meta_description` TEXT NOT NULL AFTER `listing_description`;
UPDATE `listings` SET `listing_meta_description`=`listing_description`;
ALTER TABLE `listings` DROP INDEX `seo_title`, ADD INDEX `seo_title` ( `seo_title` );

CREATE TABLE IF NOT EXISTS `listings_claims` (
  `id` int(11) NOT NULL auto_increment,
  `listing_id` int(11) NOT NULL,
  `ability_to_claim` tinyint(1) NOT NULL default '0',
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `approved` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `listing_id` (`listing_id`),
  KEY `from_user_id` (`from_user_id`,`to_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;




ALTER TABLE `levels` ADD `preapproved_mode` BOOLEAN NOT NULL AFTER `locations_number`;
UPDATE `levels` AS l INNER JOIN `types` AS t ON l.type_id=t.id SET l.preapproved_mode=t.preapproved_mode;
ALTER TABLE `types` DROP `preapproved_mode`;

ALTER TABLE `levels` ADD `meta_enabled` BOOLEAN NOT NULL DEFAULT '1' AFTER `featured`;

ALTER TABLE `levels` ADD `title_enabled` BOOLEAN NOT NULL DEFAULT '1' AFTER `featured`;
ALTER TABLE `levels` ADD `seo_title_enabled` BOOLEAN NOT NULL DEFAULT '1' AFTER `title_enabled`;

ALTER TABLE `levels` ADD `description_mode` VARCHAR(30) NOT NULL DEFAULT 'enabled' AFTER `featured`, ADD `description_length` INT NOT NULL DEFAULT '500' AFTER `description_mode`;

ALTER TABLE `levels` ADD `reviews_richtext_enabled` BOOLEAN NOT NULL DEFAULT '0' AFTER `reviews_mode`;

ALTER TABLE `levels` ADD `titles_template` VARCHAR( 255 ) NOT NULL DEFAULT '%CUSTOM_TITLE%' AFTER `reviews_richtext_enabled`;

ALTER TABLE `levels` ADD `option_report` BOOLEAN NOT NULL DEFAULT '0' AFTER `option_email_owner`;


ALTER TABLE `modules` ADD UNIQUE (`dir`);


ALTER TABLE `users` CHANGE `logo_file` `user_logo_image` VARCHAR(255) NOT NULL;
ALTER TABLE `users` ADD `seo_login` VARCHAR( 255 ) NOT NULL AFTER `login`, ADD `meta_description` TEXT NOT NULL AFTER `seo_login`, ADD `meta_keywords` TEXT NOT NULL AFTER `meta_description`;

ALTER TABLE `users_groups` ADD `use_seo_name` BOOLEAN NOT NULL DEFAULT '1' AFTER `name`;
ALTER TABLE `users_groups` ADD `is_own_page` BOOLEAN NOT NULL DEFAULT '1' AFTER `use_seo_name`;
ALTER TABLE `users_groups` ADD `meta_enabled` BOOLEAN NOT NULL DEFAULT '1' AFTER `is_own_page`;
ALTER TABLE `users_groups` ADD `logo_enabled` BOOLEAN NOT NULL DEFAULT '1' AFTER `meta_enabled`;
ALTER TABLE `users_groups` ADD `logo_size` VARCHAR(9) NOT NULL DEFAULT '147*120' AFTER `logo_enabled`;
ALTER TABLE `users_groups` ADD `logo_thumbnail_size` VARCHAR(9) NOT NULL DEFAULT '64*64' AFTER `logo_size`;

INSERT INTO `content_fields_groups` (`name`, `custom_name`, `custom_id`) VALUES
('Content fields of user profile for group "admins"', 'users_profile', 1),
('Content fields of user profile for group "members"', 'users_profile', 2);


CREATE TABLE IF NOT EXISTS `users_groups_content_permissions` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL,
  `objects_name` varchar(255) NOT NULL,
  `object_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `object_name` (`objects_name`),
  KEY `group_id` (`group_id`),
  KEY `object_id` (`object_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



ALTER TABLE `ratings` CHANGE `rating` `value` tinyint(1) NOT NULL;

UPDATE `reviews` SET `status`=1;

ALTER TABLE `types` ADD `categories_search` BOOLEAN NOT NULL DEFAULT '1' AFTER `where_search`, ADD INDEX (`categories_search`);


INSERT INTO `system_settings` (`name`, `value`) VALUES('global_categories_search', 1);
INSERT INTO `system_settings` (`name`, `value`) VALUES('path_to_terms_and_conditions', '');
INSERT INTO `system_settings` (`name`, `value`) VALUES('enable_contactus_page', 1);
INSERT INTO `system_settings` (`name`, `value`) VALUES('predefined_locations_mode', 'disabled');


ALTER TABLE `categories` ADD `type_id` INT NOT NULL DEFAULT '0' AFTER `id`, ADD INDEX (`type_id`);
ALTER TABLE `categories` ADD `order_num` INT NOT NULL DEFAULT '0' AFTER `tree_path`, ADD INDEX (`order_num`);

ALTER TABLE `listings_fields_visibility` ADD `order_by` VARCHAR( 55 ) NOT NULL DEFAULT 'l.creation_date' AFTER `format`;
ALTER TABLE `listings_fields_visibility` ADD `order_direction` VARCHAR( 4 ) NOT NULL DEFAULT 'desc' AFTER `order_by`;
ALTER TABLE `listings_fields_visibility` ADD `levels_visible` VARCHAR( 255 ) NOT NULL;

UPDATE `system_settings` SET `value`='3.0.3' WHERE `name`='W2D_VERSION';