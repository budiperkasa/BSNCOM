INSERT INTO `system_settings` (`name`, `value`) VALUES ('multilanguage_enabled', '1');
INSERT INTO `system_settings` (`name`, `value`) VALUES ('language_areas_enabled', '0');

CREATE TABLE `languages` (
  `id` int(11) NOT NULL auto_increment,
  `active` tinyint(1) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(5) NOT NULL,
  `flag` varchar(255) NOT NULL,
  `order_num` int(11) NOT NULL,
  `db_code` varchar(2) NOT NULL,
  `custom_theme` varchar(255) NOT NULL default 'default',
  `decimals_separator` varchar(1) NOT NULL default '.',
  `thousands_separator` varchar(1) NOT NULL default '',
  `date_format` varchar(55) NOT NULL default '%m/%d/%y',
  `time_format` varchar(20) NOT NULL default '%H:%M:%S',
  PRIMARY KEY  (`id`),
  KEY `order_num` (`order_num`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `languages` (`id`, `active`, `name`, `code`, `flag`, `order_num`, `db_code`, `custom_theme`, `decimals_separator`, `thousands_separator`, `date_format`, `time_format`) VALUES
(1, 1, 'English', 'en', 'flag_usa.png', 1, 'en', 'default', '.', '', '%m/%d/%y', '%H:%M:%S');