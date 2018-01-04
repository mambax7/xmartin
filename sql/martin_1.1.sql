ALTER TABLE `martin_hotel`
  CHANGE `hotel_city_id` `hotel_city_id` VARCHAR(255) NULL DEFAULT '0';

ALTER TABLE `martin_auction_bid`
  CHANGE `checck_in-time` `checck_in_time` INT(11) NOT NULL;

ALTER TABLE `martin_auction_bid`
  CHANGE `checck_in_time` `check_in_time` INT(11) NOT NULL;

ALTER TABLE `martin_room_price`
  ADD `room_sented_coupon` DECIMAL(11, 2) DEFAULT '0.00'
  AFTER `room_advisory_range_max`;

ALTER TABLE `martin_hotel`
  ADD `hotel_facility` VARCHAR(1000)
CHARACTER SET utf8
COLLATE utf8_general_ci NULL DEFAULT ''
  AFTER `hotel_reminded`;

ALTER TABLE `martin_hotel`
  CHANGE `hotel_image` `hotel_image` VARCHAR(2000)
CHARACTER SET utf8
COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `martin_hotel`
  ADD `hotel_city` INT(11) NOT NULL
COMMENT '酒店所属城市'
  AFTER `hotel_id`,
  ADD INDEX (hotel_city);

ALTER TABLE `martin_room`
  ADD `room_count` INT(11) NOT NULL DEFAULT '0'
COMMENT '客房数量'
  AFTER `hotel_id`;

ALTER TABLE `martin_hotel_service_relation`
  CHANGE `service_extra_price` `service_extra_price` DECIMAL(11, 2) NULL DEFAULT '0';

ALTER TABLE `martin_hotel_city`
  ADD `city_alias` VARCHAR(255)
CHARACTER SET utf8
COLLATE utf8_general_ci NOT NULL DEFAULT ''
  AFTER `city_name`;

ALTER TABLE `martin_order`
  ADD `order_pay` VARCHAR(25)
CHARACTER SET utf8
COLLATE utf8_general_ci NULL
  AFTER `order_pay_method`;


