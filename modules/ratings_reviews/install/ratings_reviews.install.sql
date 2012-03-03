CREATE TABLE IF NOT EXISTS `ratings` (
  `id` int(11) NOT NULL auto_increment,
  `objects_table` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `value` tinyint(1) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user_id` (`user_id`),
  KEY `object_id` (`object_id`),
  KEY `objects_table` (`objects_table`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL,
  `objects_table` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `anonym_email` varchar(255) NOT NULL,
  `anonym_name` varchar(255) NOT NULL,
  `object_id` int(11) NOT NULL,
  `review` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `objects_table` (`objects_table`),
  KEY `object_id` (`object_id`),
  KEY `parent_id` (`parent_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;