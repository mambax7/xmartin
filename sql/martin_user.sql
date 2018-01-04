ALTER TABLE `users`
  ADD `phone` VARCHAR(25)
CHARACTER SET utf8
COLLATE utf8_general_ci NOT NULL DEFAULT ''
  AFTER `user_mailok`,
  ADD `telephone` VARCHAR(30)
CHARACTER SET utf8
COLLATE utf8_general_ci NOT NULL DEFAULT ''
  AFTER `phone`,
  ADD `company` VARCHAR(255)
CHARACTER SET utf8
COLLATE utf8_general_ci NOT NULL DEFAULT ''
  AFTER `telephone`,
  ADD `nationality` INT(11) NOT NULL
  AFTER `company`,
  ADD `document` INT(11) NOT NULL
  AFTER `nationality`,
  ADD `document_value` VARCHAR(255)
CHARACTER SET utf8
COLLATE utf8_general_ci NOT NULL DEFAULT ''
  AFTER `document`,
  ADD `total_coupon` DECIMAL(11, 2) NOT NULL DEFAULT '0.00'
  AFTER `document_value`
