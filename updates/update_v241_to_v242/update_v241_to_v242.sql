ALTER TABLE `users_groups` ADD `may_register` tinyint(1) NOT NULL default '0' AFTER `default_group`;

UPDATE `users_groups` SET `may_register`='1' WHERE `default_group`='1';

INSERT INTO `system_settings` SET `name`='W2D_VERSION', `value`='2.4.2' ON DUPLICATE KEY UPDATE `value`='2.4.2';