CREATE TABLE `banners_blocks` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `mode` varchar(255) NOT NULL,
  `selector` varchar(255) NOT NULL,
  `active_years` smallint(6) NOT NULL,
  `active_months` smallint(6) NOT NULL,
  `active_days` smallint(6) NOT NULL,
  `clicks_limit` int(11) NOT NULL,
  `limit_mode` varchar(255) NOT NULL,
  `block_size` varchar(9) NOT NULL,
  `allow_remote_banners` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `banners` (
  `id` int(11) NOT NULL auto_increment,
  `block_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `banner_file` varchar(255) default NULL,
  `remote_image_url` varchar(255) NOT NULL,
  `use_remote_image` tinyint(1) NOT NULL,
  `is_uploaded_flash` tinyint(1) NOT NULL,
  `is_loaded_flash` tinyint(1) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `creation_date` datetime NOT NULL,
  `expiration_date` datetime NOT NULL,
  `was_prolonged_times` tinyint(4) NOT NULL,
  `clicks_count` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `queue` tinyint(4) NOT NULL,
  `clicks_expiration_count` int(11) NOT NULL,
  `checked_locations` TEXT NOT NULL,
  `checked_categories` TEXT NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `block_id` (`block_id`),
  KEY `owner_id` (`owner_id`),
  KEY `status` (`status`),
  KEY `queue` (`queue`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `banners_clicks_tracing` (
  `id` int(11) NOT NULL auto_increment,
  `banner_id` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `banner_id` (`banner_id`,`ip`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `banners_price` (
  `id` int(11) NOT NULL auto_increment,
  `block_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `currency` varchar(3) default NULL,
  `value` float(8,2) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `block_id` (`block_id`,`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Banner creation', 'Notification send to user when he successfully creates new banner.\r\nTokens: BANNER_ID, BANNER_URL, RECIPIENT_NAME, RECIPIENT_EMAIL', 'New banner with url ''%BANNER_URL%'' was created', 'Dear %RECIPIENT_NAME%,\n\nnew banner with url ''%BANNER_URL%'' was successfully created. If it is payment banner - banner status become active after transaction will be completed.\n\nRegards,\n%WEBSITE_TITLE%\n%WEBSITE_URL%');
INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Banner blocking', 'Notification send to user when his banner blocked by site administrator or site manager.\r\nTokens: BANNER_ID, BANNER_URL, RECIPIENT_NAME, RECIPIENT_EMAIL', 'Banner with url ''%BANNER_URL%'' was blocked', 'Dear %RECIPIENT_NAME%,\r\n\r\nYour banner with url ''%BANNER_URL%'' was blocked by site administrator or site manager. For further information contact site administration.\r\n\r\nRegards,\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');
INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Banner prolonging', 'Notification send to user when his listings sets into prolong process after it has expired.\r\nTokens: BANNER_ID, BANNER_URL, RECIPIENT_NAME, RECIPIENT_EMAIL', 'Banner with url ''%BANNER_URL%'' was prolonged', 'Dear %RECIPIENT_NAME%,\n\nbanner with url ''%BANNER_URL%'' was successfully renewed into prolongation process. If it is payment banner - banner status become active after transaction will be completed.\n\nRegards,\n%WEBSITE_TITLE%\n%WEBSITE_URL%');
INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Banner expiration', 'Notification send to user when his banner active period or clicks limit has expired.\r\nTokens: BANNER_ID, BANNER_URL, , RECIPIENT_NAME, RECIPIENT_EMAIL', 'Banner with url ''%BANNER_URL%'' has expired', 'Dear %RECIPIENT_NAME%,\r\n\r\nbanner with url ''%BANNER_URL%'' activity period or clicks limit has expired. Log in to users panel and prolong your banner.\r\n\r\nRegards,\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');