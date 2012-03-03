CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL default '0',
  `status` int(11) NOT NULL default '2',
  `login` varchar(255) default NULL,
  `seo_login` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `password` varchar(255) default NULL,
  `user_logo_image` varchar(255) NOT NULL,
  `email` varchar(255) default NULL,
  `last_ip` varchar(15) NOT NULL,
  `last_login_date` datetime NOT NULL,
  `registration_date` datetime NOT NULL,
  `registration_hash` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `status` (`status`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) NOT NULL auto_increment,
  `default_group` tinyint(1) default '0',
  `may_register` tinyint(1) NOT NULL default '0',
  `name` varchar(255) default NULL,
  `is_own_page` tinyint(1) NOT NULL default '1',
  `use_seo_name` tinyint(1) NOT NULL default '1',
  `meta_enabled` tinyint(1) NOT NULL default '1',
  `logo_enabled` tinyint(1) NOT NULL default '1',
  `logo_size` varchar(9) NOT NULL default '147*120',
  `logo_thumbnail_size` varchar(9) NOT NULL default '64*64',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `users_groups` (`id`, `default_group`, `may_register`, `name`, `is_own_page`, `use_seo_name`, `meta_enabled`, `logo_enabled`, `logo_size`, `logo_thumbnail_size`) VALUES
(1, 0, 0, 'admins', 1, 1, 1, 1, '147*120', '24*24'),
(2, 1, 1, 'members', 1, 1, 1, 1, '147*120', '24*24');



CREATE TABLE `users_groups_permissions` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL default '0',
  `function_access` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `group_id` (`group_id`,`function_access`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `users_groups_permissions` (`group_id`, `function_access`) VALUES
(1, 'Edit self profile'),
(1, 'Manage users'),
(1, 'Modules control'),
(1, 'Edit system settings'),
(1, 'Manage self listings'),
(1, 'Change listing level'),
(1, 'Manage all listings'),
(1, 'Edit categories'),
(1, 'View all statistics'),
(1, 'Manage content fields'),
(1, 'Admin backend'),
(1, 'Manage types and levels'),
(1, 'Create listings'),
(1, 'Manage payment settings'),
(1, 'View all invoices'),
(1, 'View self invoices'),
(1, 'View all transactions'),
(1, 'View self transactions'),
(1, 'Manage email notifications'),
(1, 'Manage content pages'),
(1, 'Site settings edit'),
(1, 'Edit web services settings'),
(1, 'Manage all ratings'),
(1, 'Manage all reviews'),
(1, 'Manage self reviews'),
(1, 'Manage self ratings'),
(1, 'Create transaction manually'),
(1, 'Edit listings expiration date'),
(1, 'Manage predefined locations'),
(2, 'Edit self profile'),
(2, 'Manage self listings'),
(2, 'Create listings'),
(2, 'View self invoices'),
(2, 'View self transactions'),
(2, 'View self statistics'),
(2, 'Manage self ratings'),
(2, 'Manage self reviews');


CREATE TABLE IF NOT EXISTS `users_groups_content_permissions` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` int(11) NOT NULL,
  `objects_name` varchar(255) NOT NULL,
  `object_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `object_name` (`objects_name`),
  KEY `group_id` (`group_id`),
  KEY `object_id` (`object_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;