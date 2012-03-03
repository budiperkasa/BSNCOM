DELETE FROM payment_gateways WHERE `gateway` = 'PayPal';

DELETE FROM system_settings WHERE `name` = 'paypal_email';