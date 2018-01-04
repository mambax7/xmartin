CREATE TABLE `martin_auction` (
  `auction_id`             INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `check_in_date`          INT(11)                   DEFAULT 0,
  `check_out_date`         INT(11)                   DEFAULT 0,
  `apply_start_date`       INT(11)                   DEFAULT 0,
  `apply_stop_date`        INT(11)                   DEFAULT 0,
  `auction_price`          DECIMAL(10, 2)            DEFAULT 0,
  `auction_add_price`      DECIMAL(10, 2)            DEFAULT 0,
  `auction_can_use_coupon` TINYINT(1)                DEFAULT 0,
  `auction_sented_coupon`  DECIMAL(10, 2)            DEFAULT 0,
  `auction_status`         TINYINT(1)                DEFAULT 0,
  `auction_add_time`       INT(11)                   DEFAULT 0,
  PRIMARY KEY (`auction_id`)
)
  ENGINE = MyISAM;

--
-- 导出表中的数据 `martin_auction`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_auction_room`
--

CREATE TABLE `martin_auction_room` (
  `auction_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `room_id`    INT(11) UNSIGNED NOT NULL DEFAULT '0',
  `room_count` INT(11)                   DEFAULT 0,
  PRIMARY KEY (`auction_id`, `room_id`)
)
  ENGINE = MyISAM;

--
-- 导出表中的数据 `martin_auction_room`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_group`
--

CREATE TABLE `martin_group` (
  `group_id`             INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `check_in_date`        INT(11)                   DEFAULT 0,
  `check_out_date`       INT(11)                   DEFAULT 0,
  `apply_start_date`     INT(11)                   DEFAULT 0,
  `apply_stop_date`      INT(11)                   DEFAULT 0,
  `group_price`          DECIMAL(10, 2)            DEFAULT 0,
  `group_can_use_coupon` TINYINT(1)                DEFAULT 0,
  `group_sented_coupon`  DECIMAL(10, 2)            DEFAULT 0,
  `group_status`         TINYINT(1)                DEFAULT 0,
  `group_add_time`       INT(11)                   DEFAULT 0,
  PRIMARY KEY (`group_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  AUTO_INCREMENT = 1;

--
-- 导出表中的数据 `martin_group`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_group_room`
--

CREATE TABLE `martin_group_room` (
  `group_id`   INT(11) NOT NULL DEFAULT 0,
  `room_id`    INT(11) NOT NULL DEFAULT 0,
  `room_count` INT(11)          DEFAULT 0,
  PRIMARY KEY (`group_id`, `room_id`)
)
  ENGINE = MyISAM;

--
-- 导出表中的数据 `martin_group_room`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_hotel`
--

CREATE TABLE `martin_hotel` (
  `hotel_id`             INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `hotel_city_id`        INT(11)                   DEFAULT 0,
  `hotel_rank`           INT(11)                   DEFAULT 0,
  `hotel_name`           VARCHAR(255)              DEFAULT 0,
  `hotel_enname`         VARCHAR(255)              DEFAULT NULL,
  `hotel_alias`          VARCHAR(255)              DEFAULT NULL,
  `hotel_keywords`       VARCHAR(255)              DEFAULT NULL,
  `hotel_description`    VARCHAR(255)              DEFAULT NULL,
  `hotel_star`           TINYINT(1)                DEFAULT 0,
  `hotel_address`        VARCHAR(255)              DEFAULT NULL,
  `hotel_telephone`      VARCHAR(45)               DEFAULT NULL,
  `hotel_fax`            VARCHAR(45)               DEFAULT NULL,
  `hotel_room_count`     INT(11)                   DEFAULT 0,
  `hotel_image`          VARCHAR(500)              DEFAULT NULL,
  `hotel_google`         VARCHAR(255)              DEFAULT NULL,
  `hotel_characteristic` VARCHAR(255)              DEFAULT NULL,
  `hotel_reminded`       VARCHAR(1000)             DEFAULT NULL,
  `hotel_info`           TEXT,
  `hotel_status`         TINYINT(1)                DEFAULT 0,
  `hotel_open_time`      INT(11)                   DEFAULT 0,
  `hotel_add_time`       INT(11)                   DEFAULT 0,
  PRIMARY KEY (`hotel_id`),
  KEY `hotel_city_id` (`hotel_city_id`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

--
-- 导出表中的数据 `martin_hotel`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_hotel_city`
--

CREATE TABLE `martin_hotel_city` (
  `city_id`       INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `city_parentid` INT(11)                   DEFAULT 0,
  `city_name`     VARCHAR(45)               DEFAULT NULL,
  `city_level`    VARCHAR(45)               DEFAULT NULL,
  PRIMARY KEY (`city_id`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

--
-- 导出表中的数据 `martin_hotel_city`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_hotel_promotions`
--

CREATE TABLE `martin_hotel_promotions` (
  `hotel_id`              INT(11)      DEFAULT 0,
  `promotion_start_date`  INT(11)      DEFAULT 0,
  `promotion_end_date`    INT(11)      DEFAULT 0,
  `promotion_description` VARCHAR(500) DEFAULT NULL,
  `promotion_add_time`    INT(11)      DEFAULT 0,
  KEY `hotel_id` (`hotel_id`)
)
  ENGINE = MyISAM;

--
-- 导出表中的数据 `martin_hotel_promotions`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_hotel_service`
--

CREATE TABLE `martin_hotel_service` (
  `service_id`          INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `service_type_id`     INT(11)                   DEFAULT 0,
  `service_unit`        INT(5)                    DEFAULT 0,
  `service_name`        VARCHAR(255)              DEFAULT NULL,
  `service_instruction` VARCHAR(255)              DEFAULT NULL,
  PRIMARY KEY (`service_id`),
  KEY `service_type_id` (`service_type_id`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

--
-- 导出表中的数据 `martin_hotel_service`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_hotel_service_relation`
--

CREATE TABLE `martin_hotel_service_relation` (
  `hotel_id`            INT(11) NOT NULL DEFAULT 0,
  `service_id`          INT(11) NOT NULL DEFAULT 0,
  `service_extra_price` INT(11)          DEFAULT 0,
  PRIMARY KEY (`hotel_id`, `service_id`)
)
  ENGINE = MyISAM;

--
-- 导出表中的数据 `martin_hotel_service_relation`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_hotel_service_type`
--

CREATE TABLE `martin_hotel_service_type` (
  `service_type_id`   INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `service_type_name` VARCHAR(255)              DEFAULT NULL,
  PRIMARY KEY (`service_type_id`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

--
-- 导出表中的数据 `martin_hotel_service_type`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_order`
--

CREATE TABLE `martin_order` (
  `order_id`            INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_type`          TINYINT(1)                DEFAULT 0,
  `order_mode`          TINYINT(1)                DEFAULT 0,
  `order_uid`           INT(11)                   DEFAULT 0,
  `order_status`        TINYINT(1)                DEFAULT 0,
  `order_pay_method`    TINYINT(1)                DEFAULT 0,
  `order_total_price`   DECIMAL(10, 2)            DEFAULT 0,
  `order_pay_money`     DECIMAL(10, 2)            DEFAULT 0,
  `order_coupon`        DECIMAL(10, 2)            DEFAULT 0,
  `order_real_name`     VARCHAR(45)               DEFAULT NULL,
  `order_document`      VARCHAR(255)              DEFAULT NULL,
  `order_telephone`     VARCHAR(45)               DEFAULT NULL,
  `order_phone`         VARCHAR(45)               DEFAULT 0,
  `order_extra_persons` VARCHAR(500)              DEFAULT NULL,
  `order_note`          VARCHAR(255)              DEFAULT NULL,
  `order_submit_time`   INT(11)                   DEFAULT 0,
  PRIMARY KEY (`order_id`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

--
-- 导出表中的数据 `martin_order`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_order_room`
--

CREATE TABLE `martin_order_room` (
  `order_id`   INT(11) NOT NULL DEFAULT 0,
  `room_id`    INT(11) NOT NULL DEFAULT 0,
  `room_date`  INT(11)          DEFAULT 0,
  `room_count` INT(11)          DEFAULT 0,
  PRIMARY KEY (`room_id`, `order_id`)
)
  ENGINE = MyISAM;

--
-- 导出表中的数据 `martin_order_room`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_room`
--

CREATE TABLE `martin_room` (
  `room_id`            INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `hotel_id`           INT(11)                   DEFAULT 0,
  `room_type_id`       INT(11)                   DEFAULT 0,
  `room_name`          VARCHAR(45)               DEFAULT NULL,
  `romm_area`          INT(11)                   DEFAULT 0,
  `room_floor`         VARCHAR(45)               DEFAULT NULL,
  `room_is_add_bed`    TINYINT(1)                DEFAULT 0,
  `room_add_money`     INT(11)                   DEFAULT 0,
  `romm_bed_info`      VARCHAR(255)              DEFAULT NULL,
  `room_status`        TINYINT(1)                DEFAULT 0,
  `room_sented_coupon` DECIMAL(10, 2)            DEFAULT 0,
  PRIMARY KEY (`room_id`),
  KEY `hotel_id` (`hotel_id`),
  KEY `room_type_id` (`room_type_id`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

--
-- 导出表中的数据 `martin_room`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_room_price`
--

CREATE TABLE `martin_room_price` (
  `room_id`                   INT(11)        DEFAULT 0,
  `room_is_today_special`     TINYINT(1)     DEFAULT 0,
  `room_price`                DECIMAL(10, 2) DEFAULT 0,
  `room_advisory_range_small` DECIMAL(10, 2) DEFAULT 0,
  `room_advisory_range_max`   DECIMAL(10, 2) DEFAULT 0,
  `room_date`                 INT(11)        DEFAULT 0,
  KEY `room_id` (`room_id`)
)
  ENGINE = MyISAM;

--
-- 导出表中的数据 `martin_room_price`
--


-- --------------------------------------------------------

--
-- 表的结构 `martin_room_type`
--

CREATE TABLE `martin_room_type` (
  `type_id`        INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `room_type_info` VARCHAR(45)               DEFAULT NULL,
  PRIMARY KEY (`type_id`)
)
  ENGINE = MyISAM
  AUTO_INCREMENT = 1;

--
-- 导出表中的数据 `martin_room_type`
--
