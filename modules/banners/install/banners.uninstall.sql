DROP TABLE `banners_blocks`;
DROP TABLE `banners`;
DROP TABLE `banners_clicks_tracing`;
DROP TABLE `banners_price`;

DELETE FROM `email_notification_templates` WHERE `event` = 'Banner creation';
DELETE FROM `email_notification_templates` WHERE `event` = 'Banner blocking';
DELETE FROM `email_notification_templates` WHERE `event` = 'Banner prolonging';
DELETE FROM `email_notification_templates` WHERE `event` = 'Banner expiration';