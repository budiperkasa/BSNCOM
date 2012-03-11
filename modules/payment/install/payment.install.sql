CREATE TABLE `invoices` (
  `id` int(11) NOT NULL auto_increment,
  `goods_category` varchar(255) default NULL,
  `goods_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `status` smallint(6) NOT NULL,
  `currency` varchar(3) default NULL,
  `value` float(8,2) NOT NULL,
  `fixed_price` BOOLEAN NOT NULL DEFAULT '0',
  `creation_date` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `goods` (`goods_category`,`goods_id`),
  KEY `status` (`status`),
  KEY `creation_date` (`creation_date`),
  KEY `owner_id` (`owner_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL auto_increment,
  `invoice_id` int(11) NOT NULL,
  `payment_gateway` varchar(255) NOT NULL,
  `payment_status` varchar(20) NOT NULL,
  `txn_id` varchar(255) NOT NULL,
  `mc_gross` float(8,2) NOT NULL,
  `mc_fee` float(8,2) NOT NULL,
  `mc_currency` varchar(3) default NULL,
  `quantity` smallint(6) NOT NULL,
  `payment_date` datetime NOT NULL,
  `txn_fields` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `invoice_id` (`invoice_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `payment_gateways` (
  `id` int(11) NOT NULL auto_increment,
  `gateway` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `listings_price` (
  `id` int(11) NOT NULL auto_increment,
  `level_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `currency` varchar(3) default NULL,
  `value` float(8,2) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `level_id` (`level_id`,`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `listings_payment_upgrades` (
  `id` int(11) NOT NULL auto_increment,
  `listing_id` int(11) NOT NULL,
  `old_level_id` int(11) NOT NULL,
  `new_level_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `listing_id` (`listing_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;