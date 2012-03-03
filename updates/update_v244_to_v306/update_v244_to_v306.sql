ALTER TABLE `categories` ADD `selected_icons_serialized` text NOT NULL AFTER `seo_name`;
ALTER TABLE `categories` ADD `tree_path` varchar(255) NOT NULL AFTER `parent_category_id`;
CREATE INDEX `tree_path` ON `categories` (tree_path);

ALTER TABLE `locations` ADD `tree_path` varchar(255) NOT NULL AFTER `parent_id`;
ALTER TABLE `locations` ADD `use_as_label` tinyint(1) NOT NULL AFTER `tree_path`;
ALTER TABLE `locations` ADD `geocoded_name` varchar(255) NOT NULL AFTER `use_as_label`;
CREATE INDEX `use_as_label` ON `locations` (use_as_label);

CREATE TABLE IF NOT EXISTS `map_marker_icons_themes` (
  `id` int(11) NOT NULL auto_increment,
  `folder_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `map_marker_icons_themes` (`id`, `folder_name`, `name`) VALUES
(1, '1', 'Administration, Office & Industry'),
(2, '2', 'Culture & Entertainment'),
(3, '3', 'Education & Kids'),
(4, '4', 'Events & Weather'),
(5, '5', 'Friends, Family & Real Estate'),
(6, '6', 'Media'),
(7, '7', 'Miscellaneous'),
(8, '8', 'Restaurants & Hotels'),
(9, '9', 'Sports, Health & Beauty'),
(10, '10', 'Stores'),
(11, '11', 'Tourism & Nature'),
(12, '12', 'Transportation');

CREATE TABLE IF NOT EXISTS `map_marker_icons` (
  `id` int(11) NOT NULL auto_increment,
  `folder_name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `theme_id` (`folder_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=478 ;

INSERT INTO `map_marker_icons` (`id`, `folder_name`, `file_name`, `name`) VALUES
(1, '2', 'anniversary.png', 'anniversary.png'),
(2, '2', 'aquarium.png', 'aquarium.png'),
(3, '2', 'billiard.png', 'billiard.png'),
(4, '2', 'bowling.png', 'bowling.png'),
(5, '2', 'bullfight.png', 'bullfight.png'),
(6, '2', 'casino.png', 'casino.png'),
(7, '2', 'cinema.png', 'cinema.png'),
(8, '2', 'circus.png', 'circus.png'),
(9, '2', 'club.png', 'club.png'),
(10, '2', 'dancinghall.png', 'dancinghall.png'),
(11, '2', 'festival.png', 'festival.png'),
(12, '2', 'fireworks.png', 'fireworks.png'),
(13, '2', 'magicshow.png', 'magicshow.png'),
(14, '2', 'museum-archeological.png', 'museum-archeological.png'),
(15, '2', 'museum-art.png', 'museum-art.png'),
(16, '2', 'museum-crafts.png', 'museum-crafts.png'),
(17, '2', 'museum-historical.png', 'museum-historical.png'),
(18, '2', 'museum-naval.png', 'museum-naval.png'),
(19, '2', 'museum-science.png', 'museum-science.png'),
(20, '2', 'museum-war.png', 'museum-war.png'),
(21, '2', 'museum.png', 'museum.png'),
(22, '2', 'music-classical.png', 'music-classical.png'),
(23, '2', 'music-hiphop.png', 'music-hiphop.png'),
(24, '2', 'music-live.png', 'music-live.png'),
(25, '2', 'music-rock.png', 'music-rock.png'),
(26, '2', 'party.png', 'party.png'),
(27, '2', 'poker.png', 'poker.png'),
(28, '2', 'publicart.png', 'publicart.png'),
(29, '2', 'ropescourse.png', 'ropescourse.png'),
(30, '2', 'theater.png', 'theater.png'),
(31, '2', 'themepark.png', 'themepark.png'),
(32, '2', 'waterpark.png', 'waterpark.png'),
(33, '2', 'zoo.png', 'zoo.png'),
(34, '1', 'administration.png', 'administration.png'),
(35, '1', 'amphitheater.png', 'amphitheater.png'),
(36, '1', 'atm.png', 'atm.png'),
(37, '1', 'bank.png', 'bank.png'),
(38, '1', 'bankeuro.png', 'bankeuro.png'),
(39, '1', 'bankpound.png', 'bankpound.png'),
(40, '1', 'communitycentre.png', 'communitycentre.png'),
(41, '1', 'company.png', 'company.png'),
(42, '1', 'conference.png', 'conference.png'),
(43, '1', 'court.png', 'court.png'),
(44, '1', 'currencyexchange.png', 'currencyexchange.png'),
(45, '1', 'customs.png', 'customs.png'),
(46, '1', 'dam.png', 'dam.png'),
(47, '1', 'embassy.png', 'embassy.png'),
(48, '1', 'expert.png', 'expert.png'),
(49, '1', 'factory.png', 'factory.png'),
(50, '1', 'findajob.png', 'findajob.png'),
(51, '1', 'findjob.png', 'findjob.png'),
(52, '1', 'firemen.png', 'firemen.png'),
(53, '1', 'justice.png', 'justice.png'),
(54, '1', 'laboratory.png', 'laboratory.png'),
(55, '1', 'laundromat.png', 'laundromat.png'),
(56, '1', 'lockerrental.png', 'lockerrental.png'),
(57, '1', 'military.png', 'military.png'),
(58, '1', 'mine.png', 'mine.png'),
(59, '1', 'mobilephonetower.png', 'mobilephonetower.png'),
(60, '1', 'observatory.png', 'observatory.png'),
(61, '1', 'oilpumpjack.png', 'oilpumpjack.png'),
(62, '1', 'police.png', 'police.png'),
(63, '1', 'police2.png', 'police2.png'),
(64, '1', 'postal.png', 'postal.png'),
(65, '1', 'powerlinepole.png', 'powerlinepole.png'),
(66, '1', 'powerplant.png', 'powerplant.png'),
(67, '1', 'powersubstation.png', 'powersubstation.png'),
(68, '1', 'prison.png', 'prison.png'),
(69, '1', 'sciencecenter.png', 'sciencecenter.png'),
(70, '1', 'seniorsite.png', 'seniorsite.png'),
(71, '1', 'spaceport.png', 'spaceport.png'),
(72, '1', 'watertower.png', 'watertower.png'),
(73, '1', 'windturbine.png', 'windturbine.png'),
(74, '1', 'workoffice.png', 'workoffice.png'),
(75, '3', 'daycare.png', 'daycare.png'),
(76, '3', 'library.png', 'library.png'),
(77, '3', 'nanny.png', 'nanny.png'),
(78, '3', 'nursery.png', 'nursery.png'),
(79, '3', 'playground.png', 'playground.png'),
(80, '3', 'school.png', 'school.png'),
(81, '3', 'university.png', 'university.png'),
(82, '4', 'bomb.png', 'bomb.png'),
(83, '4', 'clouds.png', 'clouds.png'),
(84, '4', 'cloudsun.png', 'cloudsun.png'),
(85, '4', 'cluster2.png', 'cluster2.png'),
(86, '4', 'cluster3.png', 'cluster3.png'),
(87, '4', 'cluster4.png', 'cluster4.png'),
(88, '4', 'cluster5.png', 'cluster5.png'),
(89, '4', 'days-dim.png', 'days-dim.png'),
(90, '4', 'days-dom.png', 'days-dom.png'),
(91, '4', 'days-jeu.png', 'days-jeu.png'),
(92, '4', 'days-jue.png', 'days-jue.png'),
(93, '4', 'days-lun.png', 'days-lun.png'),
(94, '4', 'days-mar.png', 'days-mar.png'),
(95, '4', 'days-mer.png', 'days-mer.png'),
(96, '4', 'days-mie.png', 'days-mie.png'),
(97, '4', 'days-qua.png', 'days-qua.png'),
(98, '4', 'days-qui.png', 'days-qui.png'),
(99, '4', 'days-sab.png', 'days-sab.png'),
(100, '4', 'days-sam.png', 'days-sam.png'),
(101, '4', 'days-seg.png', 'days-seg.png'),
(102, '4', 'days-sex.png', 'days-sex.png'),
(103, '4', 'days-ter.png', 'days-ter.png'),
(104, '4', 'days-ven.png', 'days-ven.png'),
(105, '4', 'days-vie.png', 'days-vie.png'),
(106, '4', 'explosion.png', 'explosion.png'),
(107, '4', 'fire.png', 'fire.png'),
(108, '4', 'flood.png', 'flood.png'),
(109, '4', 'friday.png', 'friday.png'),
(110, '4', 'gun.png', 'gun.png'),
(111, '4', 'monday.png', 'monday.png'),
(112, '4', 'planecrash.png', 'planecrash.png'),
(113, '4', 'radiation.png', 'radiation.png'),
(114, '4', 'rain.png', 'rain.png'),
(115, '4', 'revolution.png', 'revolution.png'),
(116, '4', 'satursday.png', 'satursday.png'),
(117, '4', 'snow.png', 'snow.png'),
(118, '4', 'strike.png', 'strike.png'),
(119, '4', 'strike1.png', 'strike1.png'),
(120, '4', 'sun.png', 'sun.png'),
(121, '4', 'sunday.png', 'sunday.png'),
(122, '4', 'thunder.png', 'thunder.png'),
(123, '4', 'thursday.png', 'thursday.png'),
(124, '4', 'tuesday.png', 'tuesday.png'),
(125, '4', 'wedding.png', 'wedding.png'),
(126, '4', 'wednesday.png', 'wednesday.png'),
(127, '5', 'apartment.png', 'apartment.png'),
(128, '5', 'dates.png', 'dates.png'),
(129, '5', 'family.png', 'family.png'),
(130, '5', 'friends.png', 'friends.png'),
(131, '5', 'girlfriend.png', 'girlfriend.png'),
(132, '5', 'home.png', 'home.png'),
(133, '5', 'realestate.png', 'realestate.png'),
(134, '6', 'audio.png', 'audio.png'),
(135, '6', 'photo.png', 'photo.png'),
(136, '6', 'photodown.png', 'photodown.png'),
(137, '6', 'photodownleft.png', 'photodownleft.png'),
(138, '6', 'photodownright.png', 'photodownright.png'),
(139, '6', 'photoleft.png', 'photoleft.png'),
(140, '6', 'photoright.png', 'photoright.png'),
(141, '6', 'photoup.png', 'photoup.png'),
(142, '6', 'photoupleft.png', 'photoupleft.png'),
(143, '6', 'photoupright.png', 'photoupright.png'),
(144, '6', 'text.png', 'text.png'),
(145, '6', 'video.png', 'video.png'),
(146, '7', 'bench.png', 'bench.png'),
(147, '7', 'cluster.png', 'cluster.png'),
(148, '7', 'construction.png', 'construction.png'),
(149, '7', 'disability.png', 'disability.png'),
(150, '7', 'drinkingfountain.png', 'drinkingfountain.png'),
(151, '7', 'info.png', 'info.png'),
(152, '7', 'lock.png', 'lock.png'),
(153, '7', 'recycle.png', 'recycle.png'),
(154, '7', 'regroup.png', 'regroup.png'),
(155, '7', 'shower.png', 'shower.png'),
(156, '7', 'smokingarea.png', 'smokingarea.png'),
(157, '7', 'telephone.png', 'telephone.png'),
(158, '7', 'toilets.png', 'toilets.png'),
(159, '7', 'trash.png', 'trash.png'),
(160, '7', 'waitingroom.png', 'waitingroom.png'),
(161, '7', 'wifi.png', 'wifi.png'),
(162, '8', 'bar.png', 'bar.png'),
(163, '8', 'bungalow.png', 'bungalow.png'),
(164, '8', 'cocktail.png', 'cocktail.png'),
(165, '8', 'coffee.png', 'coffee.png'),
(166, '8', 'fastfood.png', 'fastfood.png'),
(167, '8', 'foodtruck.png', 'foodtruck.png'),
(168, '8', 'gourmet.png', 'gourmet.png'),
(169, '8', 'hostel.png', 'hostel.png'),
(170, '8', 'hotel.png', 'hotel.png'),
(171, '8', 'hotel1star.png', 'hotel1star.png'),
(172, '8', 'hotel2stars.png', 'hotel2stars.png'),
(173, '8', 'hotel3stars.png', 'hotel3stars.png'),
(174, '8', 'hotel4stars.png', 'hotel4stars.png'),
(175, '8', 'hotel5stars.png', 'hotel5stars.png'),
(176, '8', 'icecream.png', 'icecream.png'),
(177, '8', 'pizza.png', 'pizza.png'),
(178, '8', 'resort.png', 'resort.png'),
(179, '8', 'restaurant-barbecue.png', 'restaurant-barbecue.png'),
(180, '8', 'restaurant-buffet.png', 'restaurant-buffet.png'),
(181, '8', 'restaurant-fish.png', 'restaurant-fish.png'),
(182, '8', 'restaurant-romantic.png', 'restaurant-romantic.png'),
(183, '8', 'restaurant.png', 'restaurant.png'),
(184, '8', 'restaurantafrican.png', 'restaurantafrican.png'),
(185, '8', 'restaurantchinese.png', 'restaurantchinese.png'),
(186, '8', 'restaurantfishchips.png', 'restaurantfishchips.png'),
(187, '8', 'restaurantgourmet.png', 'restaurantgourmet.png'),
(188, '8', 'restaurantgreek.png', 'restaurantgreek.png'),
(189, '8', 'restaurantindian.png', 'restaurantindian.png'),
(190, '8', 'restaurantitalian.png', 'restaurantitalian.png'),
(191, '8', 'restaurantjapanese.png', 'restaurantjapanese.png'),
(192, '8', 'restaurantkebab.png', 'restaurantkebab.png'),
(193, '8', 'restaurantkorean.png', 'restaurantkorean.png'),
(194, '8', 'restaurantmediterranean.png', 'restaurantmediterranean.png'),
(195, '8', 'restaurantmexican.png', 'restaurantmexican.png'),
(196, '8', 'restaurantthai.png', 'restaurantthai.png'),
(197, '8', 'restaurantturkish.png', 'restaurantturkish.png'),
(198, '8', 'tapas.png', 'tapas.png'),
(199, '8', 'teahouse.png', 'teahouse.png'),
(200, '8', 'terrace.png', 'terrace.png'),
(201, '8', 'villa.png', 'villa.png'),
(202, '8', 'winery.png', 'winery.png'),
(203, '8', 'youthhostel.png', 'youthhostel.png'),
(204, '9', 'aestheticscenter.png', 'aestheticscenter.png'),
(205, '9', 'airplane-sport.png', 'airplane-sport.png'),
(206, '9', 'archery.png', 'archery.png'),
(207, '9', 'atv.png', 'atv.png'),
(208, '9', 'australianfootball.png', 'australianfootball.png'),
(209, '9', 'baseball.png', 'baseball.png'),
(210, '9', 'basketball.png', 'basketball.png'),
(211, '9', 'baskteball2.png', 'baskteball2.png'),
(212, '9', 'bobsleigh.png', 'bobsleigh.png'),
(213, '9', 'boxing.png', 'boxing.png'),
(214, '9', 'canoe.png', 'canoe.png'),
(215, '9', 'climbing.png', 'climbing.png'),
(216, '9', 'cricket.png', 'cricket.png'),
(217, '9', 'cyclingfeedarea.png', 'cyclingfeedarea.png'),
(218, '9', 'cyclingmountain1.png', 'cyclingmountain1.png'),
(219, '9', 'cyclingmountain2.png', 'cyclingmountain2.png'),
(220, '9', 'cyclingmountain3.png', 'cyclingmountain3.png'),
(221, '9', 'cyclingmountain4.png', 'cyclingmountain4.png'),
(222, '9', 'cyclingmountainnotrated.png', 'cyclingmountainnotrated.png'),
(223, '9', 'cyclingsport.png', 'cyclingsport.png'),
(224, '9', 'cyclingsprint.png', 'cyclingsprint.png'),
(225, '9', 'cyclinguncategorized.png', 'cyclinguncategorized.png'),
(226, '9', 'dentist.png', 'dentist.png'),
(227, '9', 'diving.png', 'diving.png'),
(228, '9', 'doctor.png', 'doctor.png'),
(229, '9', 'drugs.png', 'drugs.png'),
(230, '9', 'firstaid.png', 'firstaid.png'),
(231, '9', 'fishing.png', 'fishing.png'),
(232, '9', 'fitnesscenter.png', 'fitnesscenter.png'),
(233, '9', 'followpath.png', 'followpath.png'),
(234, '9', 'golf.png', 'golf.png'),
(235, '9', 'gym.png', 'gym.png'),
(236, '9', 'hairsalon.png', 'hairsalon.png'),
(237, '9', 'handball.png', 'handball.png'),
(238, '9', 'hanggliding.png', 'hanggliding.png'),
(239, '9', 'hiking.png', 'hiking.png'),
(240, '9', 'horseriding.png', 'horseriding.png'),
(241, '9', 'hospital.png', 'hospital.png'),
(242, '9', 'hunting.png', 'hunting.png'),
(243, '9', 'icehockey.png', 'icehockey.png'),
(244, '9', 'iceskating.png', 'iceskating.png'),
(245, '9', 'jogging.png', 'jogging.png'),
(246, '9', 'judo.png', 'judo.png'),
(247, '9', 'karate.png', 'karate.png'),
(248, '9', 'karting.png', 'karting.png'),
(249, '9', 'kayak.png', 'kayak.png'),
(250, '9', 'massage.png', 'massage.png'),
(251, '9', 'motorbike.png', 'motorbike.png'),
(252, '9', 'nordicski.png', 'nordicski.png'),
(253, '9', 'ophthalmologist.png', 'ophthalmologist.png'),
(254, '9', 'personalwatercraft.png', 'personalwatercraft.png'),
(255, '9', 'pool-indoor.png', 'pool-indoor.png'),
(256, '9', 'pool.png', 'pool.png'),
(257, '9', 'racing.png', 'racing.png'),
(258, '9', 'rowboat.png', 'rowboat.png'),
(259, '9', 'rugby.png', 'rugby.png'),
(260, '9', 'sailboat-sport.png', 'sailboat-sport.png'),
(261, '9', 'sauna.png', 'sauna.png'),
(262, '9', 'schrink.png', 'schrink.png'),
(263, '9', 'skateboarding.png', 'skateboarding.png'),
(264, '9', 'skiing.png', 'skiing.png'),
(265, '9', 'skijump.png', 'skijump.png'),
(266, '9', 'skilift.png', 'skilift.png'),
(267, '9', 'snowboarding.png', 'snowboarding.png'),
(268, '9', 'snowmobiling.png', 'snowmobiling.png'),
(269, '9', 'snowshoeing.png', 'snowshoeing.png'),
(270, '9', 'soccer.png', 'soccer.png'),
(271, '9', 'soccer2.png', 'soccer2.png'),
(272, '9', 'spelunking.png', 'spelunking.png'),
(273, '9', 'stadium.png', 'stadium.png'),
(274, '9', 'surfing.png', 'surfing.png'),
(275, '9', 'suv.png', 'suv.png'),
(276, '9', 'tennis.png', 'tennis.png'),
(277, '9', 'tennis2.png', 'tennis2.png'),
(278, '9', 'usfootball.png', 'usfootball.png'),
(279, '9', 'vet.png', 'vet.png'),
(280, '9', 'waterskiing.png', 'waterskiing.png'),
(281, '9', 'windsurfing.png', 'windsurfing.png'),
(282, '9', 'yoga.png', 'yoga.png'),
(283, '9', 'zipline.png', 'zipline.png'),
(284, '10', 'artgallery.png', 'artgallery.png'),
(285, '10', 'bags.png', 'bags.png'),
(286, '10', 'bookstore.png', 'bookstore.png'),
(287, '10', 'bread.png', 'bread.png'),
(288, '10', 'butcher.png', 'butcher.png'),
(289, '10', 'clothes-female.png', 'clothes-female.png'),
(290, '10', 'clothes-male.png', 'clothes-male.png'),
(291, '10', 'clothes.png', 'clothes.png'),
(292, '10', 'computer.png', 'computer.png'),
(293, '10', 'concessionaire.png', 'concessionaire.png'),
(294, '10', 'convenience.png', 'convenience.png'),
(295, '10', 'deptstore.png', 'deptstore.png'),
(296, '10', 'fishingshop.png', 'fishingshop.png'),
(297, '10', 'flowers.png', 'flowers.png'),
(298, '10', 'gifts.png', 'gifts.png'),
(299, '10', 'grocery.png', 'grocery.png'),
(300, '10', 'hats.png', 'hats.png'),
(301, '10', 'jewelry.png', 'jewelry.png'),
(302, '10', 'liquor.png', 'liquor.png'),
(303, '10', 'movierental.png', 'movierental.png'),
(304, '10', 'music.png', 'music.png'),
(305, '10', 'newsagent.png', 'newsagent.png'),
(306, '10', 'paint.png', 'paint.png'),
(307, '10', 'patisserie.png', 'patisserie.png'),
(308, '10', 'pens.png', 'pens.png'),
(309, '10', 'perfumery.png', 'perfumery.png'),
(310, '10', 'pets.png', 'pets.png'),
(311, '10', 'phones.png', 'phones.png'),
(312, '10', 'photography.png', 'photography.png'),
(313, '10', 'shoes.png', 'shoes.png'),
(314, '10', 'shoppingmall.png', 'shoppingmall.png'),
(315, '10', 'sneakers.png', 'sneakers.png'),
(316, '10', 'supermarket.png', 'supermarket.png'),
(317, '10', 'tailor.png', 'tailor.png'),
(318, '10', 'textiles.png', 'textiles.png'),
(319, '10', 'tools.png', 'tools.png'),
(320, '10', 'toys.png', 'toys.png'),
(321, '10', 'videogames.png', 'videogames.png'),
(322, '11', 'agriculture.png', 'agriculture.png'),
(326, '11', 'airplane-tourism.png', 'airplane-tourism.png'),
(327, '11', 'amphitheater-tourism.png', 'amphitheater-tourism.png'),
(328, '11', 'ancientmonument.png', 'ancientmonument.png'),
(329, '11', 'ancienttemple.png', 'ancienttemple.png'),
(330, '11', 'ancienttempleruin.png', 'ancienttempleruin.png'),
(331, '11', 'animals.png', 'animals.png'),
(332, '11', 'arch.png', 'arch.png'),
(333, '11', 'beach.png', 'beach.png'),
(334, '11', 'beautiful.png', 'beautiful.png'),
(335, '11', 'bigcity.png', 'bigcity.png'),
(336, '11', 'bridge.png', 'bridge.png'),
(337, '11', 'bridgemodern.png', 'bridgemodern.png'),
(338, '11', 'cabin.png', 'cabin.png'),
(339, '11', 'camping.png', 'camping.png'),
(340, '11', 'campingsite.png', 'campingsite.png'),
(341, '11', 'castle.png', 'castle.png'),
(342, '11', 'cathedral.png', 'cathedral.png'),
(343, '11', 'cathedral2.png', 'cathedral2.png'),
(344, '11', 'cave.png', 'cave.png'),
(345, '11', 'cemetary.png', 'cemetary.png'),
(346, '11', 'chapel.png', 'chapel.png'),
(347, '11', 'church.png', 'church.png'),
(348, '11', 'church2.png', 'church2.png'),
(349, '11', 'citysquare.png', 'citysquare.png'),
(350, '11', 'convent.png', 'convent.png'),
(351, '11', 'corral.png', 'corral.png'),
(352, '11', 'country.png', 'country.png'),
(353, '11', 'cross.png', 'cross.png'),
(354, '11', 'cruise.png', 'cruise.png'),
(355, '11', 'dog-leash.png', 'dog-leash.png'),
(356, '11', 'dog-offleash.png', 'dog-offleash.png'),
(357, '11', 'drinkingwater.png', 'drinkingwater.png'),
(358, '11', 'farm.png', 'farm.png'),
(359, '11', 'fjord.png', 'fjord.png'),
(360, '11', 'forest.png', 'forest.png'),
(361, '11', 'fortress.png', 'fortress.png'),
(362, '11', 'fossils.png', 'fossils.png'),
(363, '11', 'fountain.png', 'fountain.png'),
(364, '11', 'garden.png', 'garden.png'),
(365, '11', 'gateswalls.png', 'gateswalls.png'),
(366, '11', 'geyser.png', 'geyser.png'),
(367, '11', 'glacier.png', 'glacier.png'),
(368, '11', 'gondola.png', 'gondola.png'),
(369, '11', 'headstone.png', 'headstone.png'),
(370, '11', 'headstonejewish.png', 'headstonejewish.png'),
(371, '11', 'hiking-tourism.png', 'hiking-tourism.png'),
(372, '11', 'historicalquarter.png', 'historicalquarter.png'),
(373, '11', 'jewishquarter.png', 'jewishquarter.png'),
(374, '11', 'lake.png', 'lake.png'),
(375, '11', 'lighthouse.png', 'lighthouse.png'),
(376, '11', 'modernmonument.png', 'modernmonument.png'),
(377, '11', 'moderntower.png', 'moderntower.png'),
(378, '11', 'monastery.png', 'monastery.png'),
(379, '11', 'monument.png', 'monument.png'),
(380, '11', 'mosque.png', 'mosque.png'),
(381, '11', 'olympicsite.png', 'olympicsite.png'),
(382, '11', 'pagoda.png', 'pagoda.png'),
(383, '11', 'palace.png', 'palace.png'),
(384, '11', 'panoramic.png', 'panoramic.png'),
(385, '11', 'panoramic180.png', 'panoramic180.png'),
(386, '11', 'park-urban.png', 'park-urban.png'),
(387, '11', 'park.png', 'park.png'),
(388, '11', 'petroglyphs.png', 'petroglyphs.png'),
(389, '11', 'picnic.png', 'picnic.png'),
(390, '11', 'places-unvisited.png', 'places-unvisited.png'),
(391, '11', 'places-visited.png', 'places-visited.png'),
(392, '11', 'rattlesnake.png', 'rattlesnake.png'),
(393, '11', 'riparian.png', 'riparian.png'),
(394, '11', 'ruins.png', 'ruins.png'),
(395, '11', 'sailboat-tourism.png', 'sailboat-tourism.png'),
(396, '11', 'seals.png', 'seals.png'),
(397, '11', 'shelter-picnic.png', 'shelter-picnic.png'),
(398, '11', 'shelter-sleeping.png', 'shelter-sleeping.png'),
(399, '11', 'shore.png', 'shore.png'),
(400, '11', 'sight.png', 'sight.png'),
(401, '11', 'smallcity.png', 'smallcity.png'),
(402, '11', 'statue.png', 'statue.png'),
(403, '11', 'synagogue.png', 'synagogue.png'),
(404, '11', 'templehindu.png', 'templehindu.png'),
(405, '11', 'tent.png', 'tent.png'),
(406, '11', 'tower.png', 'tower.png'),
(407, '11', 'villa-tourism.png', 'villa-tourism.png'),
(408, '11', 'water.png', 'water.png'),
(409, '11', 'waterfall.png', 'waterfall.png'),
(410, '11', 'watermill.png', 'watermill.png'),
(411, '11', 'waterwell.png', 'waterwell.png'),
(412, '11', 'waterwellpump.png', 'waterwellpump.png'),
(413, '11', 'wetland.png', 'wetland.png'),
(414, '11', 'windmill.png', 'windmill.png'),
(415, '11', 'wineyard.png', 'wineyard.png'),
(416, '11', 'world.png', 'world.png'),
(417, '11', 'worldheritagesite.png', 'worldheritagesite.png'),
(418, '12', 'accident.png', 'accident.png'),
(419, '12', 'aircraft-small.png', 'aircraft-small.png'),
(420, '12', 'airport-apron.png', 'airport-apron.png'),
(421, '12', 'airport-runway.png', 'airport-runway.png'),
(422, '12', 'airport-terminal.png', 'airport-terminal.png'),
(423, '12', 'airport.png', 'airport.png'),
(424, '12', 'bicycleparking.png', 'bicycleparking.png'),
(425, '12', 'bus.png', 'bus.png'),
(426, '12', 'cablecar.png', 'cablecar.png'),
(427, '12', 'car.png', 'car.png'),
(428, '12', 'carrental.png', 'carrental.png'),
(429, '12', 'carrepair.png', 'carrepair.png'),
(430, '12', 'carwash.png', 'carwash.png'),
(431, '12', 'crossingguard.png', 'crossingguard.png'),
(432, '12', 'cycling.png', 'cycling.png'),
(433, '12', 'disabledparking.png', 'disabledparking.png'),
(434, '12', 'down.png', 'down.png'),
(435, '12', 'downleft.png', 'downleft.png'),
(436, '12', 'downright.png', 'downright.png'),
(437, '12', 'downthenleft.png', 'downthenleft.png'),
(438, '12', 'downthenright.png', 'downthenright.png'),
(439, '12', 'fallingrocks.png', 'fallingrocks.png'),
(440, '12', 'gazstation.png', 'gazstation.png'),
(441, '12', 'helicopter.png', 'helicopter.png'),
(442, '12', 'highway.png', 'highway.png'),
(443, '12', 'hotairballoon.png', 'hotairballoon.png'),
(444, '12', 'left.png', 'left.png'),
(445, '12', 'leftthendown.png', 'leftthendown.png'),
(446, '12', 'leftthenup.png', 'leftthenup.png'),
(447, '12', 'mainroad.png', 'mainroad.png'),
(448, '12', 'motorcycle.png', 'motorcycle.png'),
(449, '12', 'parkandride.png', 'parkandride.png'),
(450, '12', 'parking.png', 'parking.png'),
(451, '12', 'pedestriancrossing.png', 'pedestriancrossing.png'),
(452, '12', 'port.png', 'port.png'),
(453, '12', 'right.png', 'right.png'),
(454, '12', 'rightthendown.png', 'rightthendown.png'),
(455, '12', 'rightthenup.png', 'rightthenup.png'),
(456, '12', 'sailboat.png', 'sailboat.png'),
(457, '12', 'speedhump.png', 'speedhump.png'),
(458, '12', 'steamtrain.png', 'steamtrain.png'),
(459, '12', 'stop.png', 'stop.png'),
(460, '12', 'stoplight.png', 'stoplight.png'),
(461, '12', 'subway.png', 'subway.png'),
(462, '12', 'taxi.png', 'taxi.png'),
(463, '12', 'taxiway.png', 'taxiway.png'),
(464, '12', 'tollstation.png', 'tollstation.png'),
(465, '12', 'trafficenforcementcamera.png', 'trafficenforcementcamera.png'),
(466, '12', 'train.png', 'train.png'),
(467, '12', 'tram.png', 'tram.png'),
(468, '12', 'truck.png', 'truck.png'),
(469, '12', 'tunnel.png', 'tunnel.png'),
(470, '12', 'turnleft.png', 'turnleft.png'),
(471, '12', 'turnright.png', 'turnright.png'),
(472, '12', 'up.png', 'up.png'),
(473, '12', 'upleft.png', 'upleft.png'),
(474, '12', 'upright.png', 'upright.png'),
(475, '12', 'upthenleft.png', 'upthenleft.png'),
(476, '12', 'upthenright.png', 'upthenright.png'),
(477, '12', 'vespa.png', 'vespa.png');

CREATE TABLE IF NOT EXISTS `listings_in_locations` (
  `id` int(11) NOT NULL auto_increment,
  `listing_id` int(11) NOT NULL,
  `geocoded_name` varchar(255) NOT NULL,
  `geocoded_country` varchar(255) NOT NULL,
  `geocoded_state` varchar(255) NOT NULL,
  `geocoded_city` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `address_line_1` varchar(255) NOT NULL,
  `address_line_2` varchar(255) NOT NULL,
  `zip_or_postal_index` varchar(255) NOT NULL,
  `manual_coords` tinyint(4) NOT NULL default '0',
  `map_coords_1` float NOT NULL,
  `map_coords_2` float NOT NULL,
  `map_zoom` int(11) NOT NULL,
  `map_icon_id` int(11) NOT NULL,
  `map_icon_file` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `listing_id` (`listing_id`,`map_icon_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `levels` ADD `locations_number` smallint(6) NOT NULL default '1' AFTER `categories_number`;

INSERT INTO `system_settings` (`name`, `value`) VALUES ('search_in_raduis_measure', 'miles');

INSERT INTO `users_groups_permissions` (`group_id`, `function_access`) VALUES (1, 'Edit ratings');
INSERT INTO `users_groups_permissions` (`group_id`, `function_access`) VALUES (1, 'Edit reviews');

DELETE FROM `system_settings` WHERE name='zip_or_postal_index';






























ALTER TABLE `videos` CHANGE `status` `status` VARCHAR( 30 ) NOT NULL;
ALTER TABLE `videos` CHANGE `video_id` `video_code` VARCHAR( 255 ) NOT NULL;
ALTER TABLE `videos` ADD `error_code` VARCHAR( 255 ) NOT NULL AFTER `status`;


UPDATE `system_settings` SET value='rating' WHERE name='def_listings_order' AND value='r.rating';


INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Listing change level', 'Notification sends to listing owner when his listing changes level.\r\nTokens: LISTING_ID, LISTING_TITLE, RECIPIENT_NAME, NEW_LISTING_LEVEL, OLD_LISTING_LEVEL, RECIPIENT_EMAIL, REVIEW_BODY', 'Level of your listing ''%LISTING_TITLE%'' was changed', 'Dear %RECIPIENT_NAME%,\r\n\r\nlevel of your listing "%LISTING_TITLE%" was changed from "%OLD_LISTING_LEVEL%" to "%NEW_LISTING_LEVEL%".\r\n\r\nRegards,\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');
INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Category suggestion', 'Notification sends to site manager when user suggests new category.\r\nTokens: SUGGESTED_CATEGORY, SENDER_NAME, SENDER_EMAIL, RECIPIENT_EMAIL', 'New category was suggested', 'Dear website manager,\r\n\r\nuser %SENDER_NAME% suggested new category: %SUGGESTED_CATEGORY%\r\Now you may approve and add this category into the list on categories management page.\r\nYou may notify user on this email: %SENDER_EMAIL%\r\n\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');
INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Listing claim', 'Notification sends to listing owner when user claims the listing.\r\nTokens: LISTING_TITLE, SENDER_NAME, SENDER_EMAIL, RECIPIENT_NAME, RECIPIENT_EMAIL', 'Listing was claimed', 'Dear %RECIPIENT_NAME%,\r\n\r\nuser %SENDER_NAME% claimed your listing: %LISTING_TITLE%\r\nNow you may approve or decline this claim.\r\nYou may notify user by this email: %SENDER_EMAIL%\r\n\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');
INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Listing claim approved', 'Notification sends to user when his claim on listing was approved.\r\nTokens: LISTING_TITLE, RECIPIENT_NAME, RECIPIENT_EMAIL', 'Claim was approved', 'Dear %RECIPIENT_NAME%,\r\n\r\nyour claim on listing: %LISTING_TITLE%\r\nwas approved\r\n\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');
INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Listing claim declined', 'Notification sends to user when his claim on listing was declined.\r\nTokens: LISTING_TITLE, RECIPIENT_NAME, RECIPIENT_EMAIL', 'Claim was declined', 'Dear %RECIPIENT_NAME%,\r\n\r\nyour claim on listing: %LISTING_TITLE%\r\nwas declined\r\n\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');
INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Reply on your comment', 'Notification send to user when somebody replies on his comment.\r\nTokens: LISTING_TITLE, LISTING_URL, RECIPIENT_NAME, RECIPIENT_EMAIL, REVIEW_BODY', 'New reply on your comment for listing ''%LISTING_TITLE%''', 'Dear %RECIPIENT_NAME%,\r\n\r\ncheck new reply on your comment for listing ''%LISTING_TITLE%''\r\n%LISTING_URL%\r\n\r\nComment body:\r\n%REVIEW_BODY%\r\n\r\nRegards,\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');


ALTER TABLE `listings_in_locations` ADD `predefined_location_id` INT NOT NULL AFTER `geocoded_city`;
ALTER TABLE `listings_in_locations` ADD `use_predefined_locations` BOOLEAN NOT NULL AFTER `predefined_location_id`;
ALTER TABLE `listings_in_locations` DROP `geocoded_country`, DROP `geocoded_state`, DROP `geocoded_city`;

ALTER TABLE `listings_in_locations` CHANGE `map_coords_1` `map_coords_1` FLOAT( 10, 6 ) NOT NULL , CHANGE `map_coords_2` `map_coords_2` FLOAT( 10, 6 ) NOT NULL;

ALTER TABLE `listings` ADD `listing_meta_description` TEXT NOT NULL AFTER `listing_description`;
UPDATE `listings` SET `listing_meta_description`=`listing_description`;
ALTER TABLE `listings` DROP INDEX `seo_title`, ADD INDEX `seo_title` ( `seo_title` );

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




ALTER TABLE `levels` ADD `preapproved_mode` BOOLEAN NOT NULL AFTER `locations_number`;
UPDATE `levels` AS l INNER JOIN `types` AS t ON l.type_id=t.id SET l.preapproved_mode=t.preapproved_mode;
ALTER TABLE `types` DROP `preapproved_mode`;

ALTER TABLE `levels` ADD `meta_enabled` BOOLEAN NOT NULL DEFAULT '1' AFTER `featured`;

ALTER TABLE `levels` ADD `title_enabled` BOOLEAN NOT NULL DEFAULT '1' AFTER `featured`;
ALTER TABLE `levels` ADD `seo_title_enabled` BOOLEAN NOT NULL DEFAULT '1' AFTER `title_enabled`;

ALTER TABLE `levels` ADD `description_mode` VARCHAR(30) NOT NULL DEFAULT 'enabled' AFTER `featured`, ADD `description_length` INT NOT NULL DEFAULT '500' AFTER `description_mode`;

ALTER TABLE `levels` ADD `reviews_richtext_enabled` BOOLEAN NOT NULL DEFAULT '0' AFTER `reviews_mode`;

ALTER TABLE `levels` ADD `titles_template` VARCHAR( 255 ) NOT NULL DEFAULT '%CUSTOM_TITLE%' AFTER `reviews_richtext_enabled`;

ALTER TABLE `levels` ADD `option_report` BOOLEAN NOT NULL DEFAULT '0' AFTER `option_email_owner`;


ALTER TABLE `modules` ADD UNIQUE (`dir`);


ALTER TABLE `users` CHANGE `logo_file` `user_logo_image` VARCHAR(255) NOT NULL;
ALTER TABLE `users` ADD `seo_login` VARCHAR( 255 ) NOT NULL AFTER `login`, ADD `meta_description` TEXT NOT NULL AFTER `seo_login`, ADD `meta_keywords` TEXT NOT NULL AFTER `meta_description`;

ALTER TABLE `users_groups` ADD `use_seo_name` BOOLEAN NOT NULL DEFAULT '1' AFTER `name`;
ALTER TABLE `users_groups` ADD `is_own_page` BOOLEAN NOT NULL DEFAULT '1' AFTER `use_seo_name`;
ALTER TABLE `users_groups` ADD `meta_enabled` BOOLEAN NOT NULL DEFAULT '1' AFTER `is_own_page`;
ALTER TABLE `users_groups` ADD `logo_enabled` BOOLEAN NOT NULL DEFAULT '1' AFTER `meta_enabled`;
ALTER TABLE `users_groups` ADD `logo_size` VARCHAR(9) NOT NULL DEFAULT '147*120' AFTER `logo_enabled`;
ALTER TABLE `users_groups` ADD `logo_thumbnail_size` VARCHAR(9) NOT NULL DEFAULT '64*64' AFTER `logo_size`;

INSERT INTO `content_fields_groups` (`name`, `custom_name`, `custom_id`) VALUES
('Content fields of user profile for group "admins"', 'users_profile', 1),
('Content fields of user profile for group "members"', 'users_profile', 2);


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



ALTER TABLE `ratings` CHANGE `rating` `value` tinyint(1) NOT NULL;

UPDATE `reviews` SET `status`=1;

ALTER TABLE `types` ADD `categories_search` BOOLEAN NOT NULL DEFAULT '1' AFTER `where_search`, ADD INDEX (`categories_search`);


INSERT INTO `system_settings` (`name`, `value`) VALUES('global_categories_search', 1);
INSERT INTO `system_settings` (`name`, `value`) VALUES('path_to_terms_and_conditions', '');
INSERT INTO `system_settings` (`name`, `value`) VALUES('enable_contactus_page', 1);
INSERT INTO `system_settings` (`name`, `value`) VALUES('predefined_locations_mode', 'disabled');


ALTER TABLE `categories` ADD `type_id` INT NOT NULL DEFAULT '0' AFTER `id`, ADD INDEX (`type_id`);
ALTER TABLE `categories` ADD `order_num` INT NOT NULL DEFAULT '0' AFTER `tree_path`, ADD INDEX (`order_num`);

ALTER TABLE `listings_fields_visibility` ADD `order_by` VARCHAR( 55 ) NOT NULL DEFAULT 'l.creation_date' AFTER `format`;
ALTER TABLE `listings_fields_visibility` ADD `order_direction` VARCHAR( 4 ) NOT NULL DEFAULT 'desc' AFTER `order_by`;
ALTER TABLE `listings_fields_visibility` ADD `levels_visible` VARCHAR( 255 ) NOT NULL;

UPDATE `system_settings` SET `value`='3.0.6' WHERE `name`='W2D_VERSION';