INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES
('Discount coupon assigning', 'Notification sends to user when coupon was assigned with his account.\r\nTokens: COUPON_CODE, COUPON_USAGE_COUNT_LIMIT, RECIPIENT_NAME, RECIPIENT_EMAIL', 'Discount coupon ''%COUPON_CODE%'' was assigned with your account', 'Dear %RECIPIENT_NAME%,\r\n\r\ndiscount coupon ''%COUPON_CODE%'' was assigned with your account, you may use this discount %COUPON_USAGE_COUNT_LIMIT% time(s) during invoice payments.\r\n\r\nRegards,\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');

CREATE TABLE `discount_coupons` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `allowed_goods_serialized` text NOT NULL,
  `assign_events_serialized` text NOT NULL,
  `usage_count_limit_all` int(11) NOT NULL,
  `usage_count_limit_user` int(11) NOT NULL,
  `value` float(8,2) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `use_if_assigned` tinyint(1) NOT NULL,
  `discount_type` tinyint(1) NOT NULL,
  `effective_date` date NOT NULL,
  `expiration_date` date NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `code` (`code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `discount_coupons_usage` (
  `id` int(11) NOT NULL auto_increment,
  `coupon_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `usage_date` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `invoice_id` (`invoice_id`),
  KEY `coupon_id` (`coupon_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `discount_coupons_users` (
  `id` int(11) NOT NULL auto_increment,
  `coupon_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `coupon_id` (`coupon_id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;