ALTER TABLE `users`
  ADD `total_coupon` DECIMAL(11, 2) NOT NULL DEFAULT '0.00'
  AFTER `document_value`;
