ALTER TABLE `categories` ADD `meta_title` varchar(255) NOT NULL AFTER `seo_name`;
ALTER TABLE `categories` ADD `meta_description` varchar(255) NOT NULL AFTER `meta_title`;

ALTER TABLE `categories_by_type` ADD `meta_title` varchar(255) NOT NULL AFTER `seo_name`;
ALTER TABLE `categories_by_type` ADD `meta_description` varchar(255) NOT NULL AFTER `meta_title`;

ALTER TABLE `types` ADD `meta_title` varchar(255) NOT NULL AFTER `seo_name`;
ALTER TABLE `types` ADD `meta_description` varchar(255) NOT NULL AFTER `meta_title`;

ALTER TABLE `content_pages` ADD `meta_title` varchar(255) NOT NULL AFTER `title`;
ALTER TABLE `content_pages` ADD `meta_description` varchar(255) NOT NULL AFTER `meta_title`;

INSERT INTO `system_settings` SET `name`='W2D_VERSION', `value`='2.4.4' ON DUPLICATE KEY UPDATE `value`='2.4.4';