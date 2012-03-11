INSERT INTO `system_settings` (`name`, `value`) VALUES
('facebook_api_key', ''),
('facebook_app_id', ''),
('facebook_app_secret', '');

ALTER TABLE `users` ADD `facebook_uid` varchar(30) NOT NULL;
ALTER TABLE `users` ADD `use_facebook_logo` tinyint(1) NOT NULL default '1';
ALTER TABLE `users` ADD `facebook_logo_file` varchar(255) NOT NULL;

INSERT INTO `email_notification_templates` (`event`, `description`, `subject`, `body`) VALUES ('Facebook account creation', 'A notification sends to user when he logs in with facebook credentials at first time.\r\nTokens: PASSWORD, RECIPIENT_NAME, RECIPIENT_EMAIL', 'You was successfully registered', 'Dear %RECIPIENT_NAME%,\r\n\r\nYour account was successfully registered in the system. Now you may log in into account with your facebook credentials or with these credentials\r\nemail: %RECIPIENT_EMAIL%\r\npassword: %PASSWORD%\r\n\r\nYou may create/edit/delete your listings, invoices, transactions.\r\n\r\nRegards,\r\n%WEBSITE_TITLE%\r\n%WEBSITE_URL%');