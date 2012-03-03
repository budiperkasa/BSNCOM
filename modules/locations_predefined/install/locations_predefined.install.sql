CREATE TABLE `locations` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL default '0',
  `tree_path` varchar(255) NOT NULL,
  `use_as_label` tinyint(1) NOT NULL,
  `name` varchar(255) default 'untranslated',
  `seo_name` varchar(255) NOT NULL default '',
  `geocoded_name` varchar(255) NOT NULL,
  `en_name` varchar(255) NOT NULL default 'untranslated',
  PRIMARY KEY  (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `name` (`name`),
  KEY `use_as_label` (`use_as_label`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `locations_levels` (
  `id` int(11) NOT NULL auto_increment,
  `order_num` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `locations_levels` VALUES (1, 1, 'Country');
INSERT INTO `locations_levels` VALUES (2, 2, 'State');
INSERT INTO `locations_levels` VALUES (3, 3, 'City');