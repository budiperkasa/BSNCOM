CREATE TABLE `content_pages` (
  `id` int(11) NOT NULL auto_increment,
  `order_num` int(11) NOT NULL,
  `url` varchar(255) default NULL,
  `title` varchar(255) default NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `in_menu` tinyint(1) NOT NULL default '0',
  `creation_date` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `order_num` (`order_num`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `content_pages` VALUES (1, 1, 'about', 'About us', '', '', 1, '2009-07-10 00:58:28');