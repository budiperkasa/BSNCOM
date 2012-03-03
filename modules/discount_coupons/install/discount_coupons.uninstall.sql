DROP TABLE `discount_coupons`;
DROP TABLE `discount_coupons_usage`;
DROP TABLE `discount_coupons_users`;

DELETE FROM `email_notification_templates` WHERE event='Discount coupon assigning';