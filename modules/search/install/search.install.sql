CREATE TABLE `search_fields_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `custom_name` varchar(255) NOT NULL,
  `custom_id` int(11) NOT NULL,
  `mode` varchar(25) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `custom_name` (`custom_name`,`custom_id`,`mode`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `search_fields_groups` VALUES (1, 'Search fields of the global search', 'global_search', 0, 'quick');
INSERT INTO `search_fields_groups` VALUES (2, 'Search fields of the global search', 'global_search', 0, 'advanced');

CREATE TABLE `search_fields_to_groups` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `search_group_id` int(11) NOT NULL,
  `order_num` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `field_id` (`field_id`,`search_group_id`),
  KEY `order_num` (`order_num`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `search_fields_type_email` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `search_type` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `field_id` (`field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `search_fields_type_price` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `search_type` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `field_id` (`field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `search_fields_type_price_options` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `option_name` varchar(255) default 'untranslated',
  `order_num` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `search_fields_type_richtext` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `search_type` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `field_id` (`field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `search_fields_type_text` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `search_type` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `field_id` (`field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `search_fields_type_varchar` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `search_type` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `field_id` (`field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `search_fields_type_varchar_options` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `option_name` varchar(255) default 'untranslated',
  `order_num` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `search_fields_type_website` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `search_type` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `field_id` (`field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;