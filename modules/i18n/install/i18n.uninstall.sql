DROP TABLE `languages`;

DELETE FROM `system_settings` WHERE name='multilanguage_enabled';
DELETE FROM `system_settings` WHERE name='language_areas_enabled';