INSERT INTO `system_settings` (`name`, `value`) VALUES ('packages_listings_creation_mode', 'both_mode');

INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES
('Package addition', 'Notification sends to user when new package was assigned with his account.\r\nTokens: PACKAGE_ID, PACKAGE_NAME, RECIPIENT_NAME, RECIPIENT_EMAIL', 'Package ''%PACKAGE_NAME%'' was added to your account', 'Dear %RECIPIENT_NAME%,\r\n\r\npackage ''%PACKAGE_NAME%'' was added to your account, now you may create listings under this package.\r\n\r\nRegards,\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%'),
('Package blocking', 'Notification sends to package owner when his package was blocked.\r\nTokens: PACKAGE_ID, PACKAGE_NAME, RECIPIENT_NAME, RECIPIENT_EMAIL', 'Your package ''%PACKAGE_NAME%'' was blocked', 'Dear %RECIPIENT_NAME%,\r\n\r\nyour package ''%PACKAGE_NAME%'' was blocked, now you can not create listings under this package.\r\n\r\nRegards,\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');

CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(11) NOT NULL auto_increment,
  `order_num` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `order_num` (`order_num`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `packages_items` (
  `id` int(11) NOT NULL auto_increment,
  `package_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `listings_count` varchar(55) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `package_id` (`package_id`,`level_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `packages_listings` (
  `id` int(11) NOT NULL auto_increment,
  `user_package_id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_package_id` (`user_package_id`,`listing_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `packages_price` (
  `id` int(11) NOT NULL auto_increment,
  `package_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `value` float(8,2) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `package_id` (`package_id`,`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `packages_users` (
  `id` int(11) NOT NULL auto_increment,
  `package_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `package_id` (`package_id`,`user_id`,`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;