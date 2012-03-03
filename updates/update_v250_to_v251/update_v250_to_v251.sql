ALTER TABLE `listings_in_locations` ADD `geocoded_name` VARCHAR( 255 ) NOT NULL AFTER `geonameId`, ADD INDEX ( `geocoded_name` );

ALTER TABLE `listings_in_locations` ADD `geocoded_country` VARCHAR( 255 ) NOT NULL AFTER `geocoded_name`, ADD `geocoded_state` VARCHAR( 255 ) NOT NULL AFTER `geocoded_country`, ADD `geocoded_city` VARCHAR( 255 ) NOT NULL AFTER `geocoded_state`;

ALTER TABLE `listings_in_locations` DROP `geonameId`;

DELETE FROM `modules` WHERE `dir` = 'locations';
DELETE FROM `language_files` WHERE `module` = 'Locations';