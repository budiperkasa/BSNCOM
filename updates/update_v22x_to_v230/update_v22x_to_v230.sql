INSERT INTO `system_settings` (`name`, `value`) VALUES
('anonym_rates_reviews', '1'),
('company_details', 'Company Name\n123 S. Street\nState, City, Country\n(000) 000-0000'),
('single_type_structure', '0'),
('categories_block_type', 'categories-bar');

ALTER TABLE `levels` ADD `ratings_enabled` tinyint(1) NOT NULL;
ALTER TABLE `levels` ADD `reviews_mode` varchar(255) NOT NULL default 'disabled';

ALTER TABLE `listings` ADD `last_modified_date` datetime NOT NULL AFTER `expiration_date`;

CREATE TABLE IF NOT EXISTS `ratings` (
  `id` int(11) NOT NULL auto_increment,
  `objects_table` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `object_id` (`object_id`),
  KEY `objects_table` (`objects_table`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL,
  `objects_table` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `anonym_email` varchar(255) NOT NULL,
  `anonym_name` varchar(255) NOT NULL,
  `object_id` int(11) NOT NULL,
  `review` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `objects_table` (`objects_table`),
  KEY `object_id` (`object_id`),
  KEY `parent_id` (`parent_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('onReviewCreationForListing', 'Notification sends to listing owner when somebody places review on his listing.\r\nTokens: LISTING_TITLE, RECIPIENT_NAME, RECIPIENT_EMAIL, REVIEW_BODY', 'New review has been placed on your listing ''%LISTING_TITLE%''', 'Dear %RECIPIENT_NAME%,\r\n\r\nnew review has been placed on your listing ''%LISTING_TITLE%''.\r\n\r\nReview body:\r\n%REVIEW_BODY%\r\n\r\nRegards,\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');

ALTER TABLE `users` ADD `logo_file` varchar(255) NOT NULL;

INSERT INTO `modules` (`dir`, `name`) VALUES ('ratings_reviews', 'Ratings and Reviews');
INSERT INTO `language_files` (`module`, `file`) VALUES ('Ratings and Reviews', 'ratings_reviews.php');

ALTER TABLE `content_fields` ADD `frontend_name` varchar(255) NOT NULL AFTER `name`;
CREATE INDEX `frontend_name` ON `content_fields` (frontend_name);

INSERT INTO `system_settings` SET `name`='W2D_VERSION', `value`='2.3.0' ON DUPLICATE KEY UPDATE `value`='2.3.0';