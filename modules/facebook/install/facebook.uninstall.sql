ALTER TABLE `users` DROP COLUMN `facebook_uid`;
ALTER TABLE `users` DROP COLUMN `use_facebook_logo`;
ALTER TABLE `users` DROP COLUMN `facebook_logo_file`;

DELETE FROM `system_settings` WHERE `name` = 'facebook_api_key';
DELETE FROM `system_settings` WHERE `name` = 'facebook_app_id';
DELETE FROM `system_settings` WHERE `name` = 'facebook_app_secret';

DELETE FROM `email_notification_templates` WHERE `event` = 'Facebook account creation';