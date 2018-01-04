ALTER TABLE `7mogjl_martin_group_join`
  ADD `real_name` VARCHAR(255) NOT NULL DEFAULT ''
  AFTER `uid`,
  ADD `telephone` VARCHAR(45) NOT NULL DEFAULT ''
  AFTER `real_name`,
  ADD `phone` VARCHAR(45) NOT NULL DEFAULT ''
  AFTER `telephone`;

ALTER TABLE `7mogjl_martin_auction_bid`
  ADD `real_name` VARCHAR(255) NOT NULL DEFAULT ''
  AFTER `uid`,
  ADD `telephone` VARCHAR(45) NOT NULL DEFAULT ''
  AFTER `real_name`,
  ADD `phone` VARCHAR(45) NOT NULL DEFAULT ''
  AFTER `telephone`;

ALTER TABLE `7mogjl_martin_group_join`
  ADD `check_in_time` INT(11) NOT NULL
  AFTER `phone`,
  ADD `check_out_time` INT(11) NOT NULL
  AFTER `check_in_time`;

ALTER TABLE `7mogjl_martin_group_join`
  ADD `relation_order` INT(11) NOT NULL
COMMENT '关联订单'
  AFTER `join_time`,
  ADD INDEX (relation_order);
ALTER TABLE `7mogjl_martin_auction_bid`
  ADD `relation_order` INT(11) NOT NULL
COMMENT '关联订单'
  AFTER `bid_time`,
  ADD INDEX (relation_order);

ALTER TABLE `7mogjl_martin_hotel`
  CHANGE `hotel_open_time`  `hotel_open_time` VARCHAR(255) NULL DEFAULT ''
COMMENT '开业时间';


