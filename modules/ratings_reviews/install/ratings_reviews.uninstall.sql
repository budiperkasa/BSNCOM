DROP TABLE `ratings`;
DROP TABLE `reviews`;

DELETE FROM `users_groups_permissions` WHERE `function_access` = 'Edit ratings';
DELETE FROM `users_groups_permissions` WHERE `function_access` = 'Edit reviews';

DELETE FROM `email_notification_templates` WHERE `event` = 'onReviewCreationForListing';
DELETE FROM `system_settings` WHERE `name` = 'anonym_rates_reviews';