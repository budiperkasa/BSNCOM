CREATE TABLE IF NOT EXISTS `themes` (
  `id` int(11) NOT NULL auto_increment,
  `dir` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `installed` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `system_settings` (`name`, `value`) VALUES('geocoded_locations_mode_districts', 1);
INSERT INTO `system_settings` (`name`, `value`) VALUES('geocoded_locations_mode_provinces', '1');

ALTER TABLE `listings` ADD FULLTEXT (
`title` ,
`listing_description` ,
`listing_meta_description` ,
`listing_keywords`
);
ALTER TABLE `listings` ADD FULLTEXT (
`title`
);
ALTER TABLE `listings` ADD FULLTEXT (
`listing_description`
);
ALTER TABLE `listings` ADD FULLTEXT (
`listing_meta_description`
);
ALTER TABLE `listings` ADD FULLTEXT (
`listing_keywords`
);


ALTER TABLE `categories` ADD FULLTEXT (
`name` ,
`meta_title` ,
`meta_description`
);
ALTER TABLE `categories` ADD FULLTEXT (
`name`
);
ALTER TABLE `categories` ADD FULLTEXT (
`meta_title`
);
ALTER TABLE `categories` ADD FULLTEXT (
`meta_description`
);

ALTER TABLE `listings_in_locations` ADD FULLTEXT (
`geocoded_name` ,
`location` ,
`address_line_1` ,
`address_line_2` ,
`zip_or_postal_index`
);
ALTER TABLE `listings_in_locations` ADD FULLTEXT (
`geocoded_name`
);
ALTER TABLE `listings_in_locations` ADD FULLTEXT (
`location`
);
ALTER TABLE `listings_in_locations` ADD FULLTEXT (
`address_line_1`
);
ALTER TABLE `listings_in_locations` ADD FULLTEXT (
`address_line_2`
);
ALTER TABLE `listings_in_locations` ADD FULLTEXT (
`zip_or_postal_index`
);

CREATE TABLE IF NOT EXISTS `content_fields_type_datesmultiple_data` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`field_id` INT NOT NULL ,
`custom_name` VARCHAR( 255 ) NOT NULL ,
`object_id` VARCHAR( 255 ) NOT NULL ,
`field_value` DATE NOT NULL ,
`cycle_days_monday` TINYINT( 1 ) NOT NULL ,
`cycle_days_tuesday` TINYINT( 1 ) NOT NULL ,
`cycle_days_wednesday` TINYINT( 1 ) NOT NULL ,
`cycle_days_thursday` TINYINT( 1 ) NOT NULL ,
`cycle_days_friday` TINYINT( 1 ) NOT NULL ,
`cycle_days_saturday` TINYINT( 1 ) NOT NULL ,
`cycle_days_sunday` TINYINT( 1 ) NOT NULL ,
KEY `custom_name` (`custom_name`),
KEY `field_id` (`field_id`),
KEY `object_id` (`object_id`),
KEY `field_value` (`field_value`),
KEY `cycle_days` (`cycle_days_monday`,`cycle_days_tuesday`,`cycle_days_wednesday`,`cycle_days_thursday`,`cycle_days_friday`,`cycle_days_saturday`,`cycle_days_sunday`)
) ENGINE = MYISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;

INSERT INTO `system_settings` (`name`, `value`) VALUES('mollom_public_key', '');
INSERT INTO `system_settings` (`name`, `value`) VALUES('mollom_private_key', '');
INSERT INTO `site_settings` (`name`, `value`) VALUES('signature_in_emails', '');

INSERT INTO `content_fields_groups` (`name`, `custom_name`, `custom_id`) VALUES ('Content fields of contact us page', 'contact_us_page', 0);

ALTER TABLE `levels` ADD `allow_to_edit_active_period` TINYINT NOT NULL DEFAULT '0' AFTER `titles_template`;

UPDATE `system_settings` SET `value`='3.1.0' WHERE `name`='W2D_VERSION';