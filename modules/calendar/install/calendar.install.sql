CREATE TABLE IF NOT EXISTS `calendar_settings` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `connected_type_id` int(11) NOT NULL,
  `connected_field` varchar(255) NOT NULL,
  `visibility_on_index` int(11) NOT NULL,
  `visibility_for_all_types` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `calendar_settings` (`id`, `name`, `connected_type_id`, `connected_field`, `visibility_on_index`, `visibility_for_all_types`) VALUES
(1, 'Find Recent listings', 0, 'search_creation_date', 1, 1);