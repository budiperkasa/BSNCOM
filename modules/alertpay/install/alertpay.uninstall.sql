DELETE FROM `payment_gateways` WHERE `gateway` = 'AlertPay';

DELETE FROM `system_settings` WHERE `name` = 'alertpay_email';
DELETE FROM `system_settings` WHERE `name` = 'alertpay_securitycode';