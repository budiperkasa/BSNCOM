CREATE TABLE `content_fields` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `frontend_name` varchar(255) default NULL,
  `seo_name` varchar(255) default NULL,
  `type` varchar(255) default NULL,
  `type_name` varchar(255) NOT NULL,
  `configuration_page` tinyint(1) NOT NULL,
  `search_configuration_page` tinyint(1) NOT NULL,
  `description` text,
  `required` tinyint(1) NOT NULL,
  `v_index_page` tinyint(1) NOT NULL,
  `v_types_page` tinyint(1) NOT NULL,
  `v_categories_page` tinyint(1) NOT NULL,
  `v_search_page` tinyint(1) NOT NULL,
  `v_quicklist_page` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `seo_name` (`seo_name`),
  KEY `v_index_page` (`v_index_page`),
  KEY `v_types_page` (`v_types_page`),
  KEY `v_categories_page` (`v_categories_page`),
  KEY `v_search_page` (`v_search_page`),
  KEY `v_quicklist_page` (`v_quicklist_page`),
  KEY `frontend_name` (`frontend_name`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `content_fields` VALUES (1, 'Information', '', 'information', 'richtext', 'Rich text editor', '1', '1', '', '1', '0', '0', '0', '0', '0');


CREATE TABLE `content_fields_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `custom_name` varchar(255) NOT NULL,
  `custom_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `custom_id` (`custom_id`),
  KEY `custom_name` (`custom_name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `content_fields_groups` VALUES (1, 'Content fields of content pages', 'content_pages', 0);

INSERT INTO `content_fields_groups` VALUES (2, 'Content fields of contact us page', 'contact_us_page', 0);

INSERT INTO `content_fields_groups` (`name`, `custom_name`, `custom_id`) VALUES
('Content fields of user profile for group "admins"', 'users_profile', 1),
('Content fields of user profile for group "members"', 'users_profile', 2);


CREATE TABLE `content_fields_to_groups` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `order_num` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `field_id` (`field_id`,`group_id`),
  KEY `order_num` (`order_num`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `content_fields_to_groups` VALUES (1, 1, 1, 1);

CREATE TABLE `content_fields_type_checkboxes` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `order_num` int(11) NOT NULL,
  `option_name` varchar(255) default 'untranslated',
  PRIMARY KEY  (`id`),
  KEY `field_id` (`field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_checkboxes_data` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `custom_name` varchar(255) default NULL,
  `object_id` varchar(255) default NULL,
  `field_value` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `field_id` (`field_id`),
  KEY `custom_group` (`custom_name`),
  KEY `object_id` (`object_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_datetime` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `format` varchar(255) default NULL,
  `enable_time` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `field_id` (`field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_datetime_data` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `custom_name` varchar(255) default NULL,
  `object_id` varchar(255) default NULL,
  `field_value` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `field_id` (`field_id`),
  KEY `custom_group` (`custom_name`),
  KEY `object_id` (`object_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_datetimerange` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `format` varchar(255) default NULL,
  `enable_time` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `field_id` (`field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_datetimerange_data` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `custom_name` varchar(255) default NULL,
  `object_id` varchar(255) default NULL,
  `from_field_value` datetime default NULL,
  `to_field_value` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `field_id` (`field_id`),
  KEY `custom_group` (`custom_name`),
  KEY `object_id` (`object_id`),
  KEY `from_field_value` (`from_field_value`),
  KEY `to_field_value` (`to_field_value`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE = MYISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_email_data` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `custom_name` varchar(255) default NULL,
  `object_id` varchar(255) default NULL,
  `field_value` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `field_id` (`field_id`),
  KEY `custom_group` (`custom_name`),
  KEY `object_id` (`object_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_price` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `order_num` int(11) NOT NULL,
  `option_name` varchar(255) default 'untranslated',
  PRIMARY KEY  (`id`),
  KEY `field_id` (`field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_price_data` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `custom_name` varchar(255) default NULL,
  `object_id` varchar(255) default NULL,
  `field_currency` int(11) default NULL,
  `field_value` float(12,2) default NULL,
  PRIMARY KEY  (`id`),
  KEY `field_id` (`field_id`),
  KEY `custom_group` (`custom_name`),
  KEY `object_id` (`object_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_richtext` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `option_name` varchar(255) default NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `field_id` (`field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_richtext_data` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `custom_name` varchar(255) default NULL,
  `object_id` varchar(255) default NULL,
  `field_value` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `field_id` (`field_id`),
  KEY `custom_group` (`custom_name`),
  KEY `object_id` (`object_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_select` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `order_num` int(11) NOT NULL,
  `option_name` varchar(255) default 'untranslated',
  PRIMARY KEY  (`id`),
  KEY `field_id` (`field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_select_data` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `custom_name` varchar(255) default NULL,
  `object_id` varchar(255) default NULL,
  `field_value` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `field_id` (`field_id`),
  KEY `custom_group` (`custom_name`),
  KEY `object_id` (`object_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_text` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `max_length` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `field_id` (`field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_text_data` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `custom_name` varchar(255) default NULL,
  `object_id` varchar(255) default NULL,
  `field_value` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `field_id` (`field_id`),
  KEY `custom_group` (`custom_name`),
  KEY `object_id` (`object_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_varchar` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `max_length` varchar(255) NOT NULL,
  `regex` varchar(255) NOT NULL,
  `is_numeric` tinyint(1) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `field_id_2` (`field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_varchar_data` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `custom_name` varchar(255) default NULL,
  `object_id` varchar(255) default NULL,
  `field_value` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `field_id` (`field_id`),
  KEY `custom_group` (`custom_name`),
  KEY `object_id` (`object_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_website` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `enable_redirect` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `field_id` (`field_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `content_fields_type_website_data` (
  `id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `custom_name` varchar(255) default NULL,
  `object_id` varchar(255) default NULL,
  `field_value` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `field_id` (`field_id`),
  KEY `custom_group` (`custom_name`),
  KEY `object_id` (`object_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;