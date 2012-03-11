DROP TABLE `packages`;
DROP TABLE `packages_items`;
DROP TABLE `packages_listings`;
DROP TABLE `packages_price`;
DROP TABLE `packages_users`;

DELETE FROM `system_settings` WHERE name='packages_listings_creation_mode';

DELETE FROM `email_notification_templates` WHERE event='Package addition' OR event='Package blocking';