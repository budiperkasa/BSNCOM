CREATE TABLE IF NOT EXISTS `listings` (
  `id` int(11) NOT NULL auto_increment,
  `level_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `seo_title` varchar(255) NOT NULL,
  `listing_description` text NOT NULL,
  `listing_meta_description` text NOT NULL,
  `listing_keywords` text NOT NULL,
  `logo_file` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `creation_date` datetime NOT NULL,
  `expiration_date` datetime NOT NULL,
  `last_modified_date` datetime NOT NULL,
  `was_prolonged_times` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `level_id` (`level_id`),
  KEY `owner_id` (`owner_id`),
  KEY `creation_date` (`creation_date`),
  KEY `status` (`status`),
  KEY `seo_title` (`seo_title`),
  FULLTEXT KEY `title` (`title`,`listing_description`,`listing_meta_description`,`listing_keywords`),
  FULLTEXT KEY `title_2` (`title`),
  FULLTEXT KEY `listing_description` (`listing_description`),
  FULLTEXT KEY `listing_meta_description` (`listing_meta_description`),
  FULLTEXT KEY `listing_keywords` (`listing_keywords`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `listings_in_categories` (
  `id` int(11) NOT NULL auto_increment,
  `listing_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `listing_id` (`listing_id`,`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `listings_in_locations` (
  `id` int(11) NOT NULL auto_increment,
  `listing_id` int(11) NOT NULL,
  `geocoded_name` varchar(255) NOT NULL,
  `predefined_location_id` int(11) NOT NULL,
  `use_predefined_locations` tinyint(1) NOT NULL,
  `location` varchar(255) NOT NULL,
  `address_line_1` varchar(255) NOT NULL,
  `address_line_2` varchar(255) NOT NULL,
  `zip_or_postal_index` varchar(255) NOT NULL,
  `manual_coords` tinyint(4) NOT NULL default '0',
  `map_coords_1` float(10,6) NOT NULL,
  `map_coords_2` float(10,6) NOT NULL,
  `map_zoom` int(11) NOT NULL,
  `map_icon_id` int(11) NOT NULL,
  `map_icon_file` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `listing_id` (`listing_id`,`map_icon_id`),
  FULLTEXT KEY `geocoded_name` (`geocoded_name`,`location`,`address_line_1`,`address_line_2`,`zip_or_postal_index`),
  FULLTEXT KEY `geocoded_name_2` (`geocoded_name`),
  FULLTEXT KEY `location` (`location`),
  FULLTEXT KEY `address_line_1` (`address_line_1`),
  FULLTEXT KEY `address_line_2` (`address_line_2`),
  FULLTEXT KEY `zip_or_postal_index` (`zip_or_postal_index`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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

CREATE TABLE `listings_fields_visibility` (
  `id` int(11) NOT NULL auto_increment,
  `type_id` int(11) NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `view` varchar(255) NOT NULL,
  `format` varchar(255) NOT NULL,
  `order_by` varchar(55) NOT NULL default 'l.creation_date',
  `order_direction` VARCHAR( 4 ) NOT NULL DEFAULT 'desc',
  `levels_visible` VARCHAR( 255 ) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `type_id` (`type_id`,`page_name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `files` (
  `id` int(11) NOT NULL auto_increment,
  `listing_id` int(11) NOT NULL,
  `title` varchar(255) default NULL,
  `file` varchar(255) NOT NULL,
  `creation_date` datetime NOT NULL,
  `file_size` varchar(12) default NULL,
  `file_format` varchar(9) default NULL,
  PRIMARY KEY  (`id`),
  KEY `listing_id` (`listing_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `images` (
  `id` int(11) NOT NULL auto_increment,
  `listing_id` int(11) NOT NULL,
  `title` varchar(255) default NULL,
  `file` varchar(255) NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `listing_id` (`listing_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `videos` (
  `id` int(11) NOT NULL auto_increment,
  `listing_id` int(11) NOT NULL,
  `mode` varchar(255) NOT NULL,
  `status` varchar(30) NOT NULL,
  `error_code` varchar(255) NOT NULL, 
  `title` varchar(255) default NULL,
  `video_code` varchar(255) NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `listing_id` (`listing_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;