ALTER TABLE `language_files` ADD `theme` varchar(255) NOT NULL AFTER `module`;

ALTER TABLE `levels` ADD `option_pdf` tinyint(1) NOT NULL default '1' AFTER `option_print`;

ALTER TABLE `listings_in_categories` ADD `type_id` int(11) NOT NULL DEFAULT '0';

INSERT INTO `system_settings` (`name`, `value`) VALUES ('def_listings_order', 'l.creation_date');
INSERT INTO `system_settings` (`name`, `value`) VALUES ('def_listings_order_direction', 'desc');

INSERT INTO `system_settings` SET `name`='W2D_VERSION', `value`='2.4.1' ON DUPLICATE KEY UPDATE `value`='2.4.1';