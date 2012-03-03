CREATE TABLE `js_advertisement_blocks` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `mode` varchar(255) NOT NULL,
  `selector` varchar(255) NOT NULL,
  `code` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `js_advertisement_blocks` VALUES (1, 'Left sidebar adsense block', 'post', '#left_sidebar', '');