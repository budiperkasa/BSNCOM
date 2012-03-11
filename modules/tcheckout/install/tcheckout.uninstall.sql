DELETE FROM payment_gateways WHERE `module` = 'tcheckout';

DELETE FROM system_settings WHERE `name` = '2checkout_sid';
DELETE FROM system_settings WHERE `name` = '2checkout_secret_word';