-- phpMyAdmin SQL Dump
-- version 3.1.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2010 年 05 月 18 日 17:05
-- 服务器版本: 5.1.37
-- PHP 版本: 5.2.10-2ubuntu6.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `xoops_gjl`
--

-- --------------------------------------------------------

--
-- 表的结构 `xmartin_auction`
--

CREATE TABLE `xmartin_auction` (
  `auction_id`             INT(11) NOT NULL AUTO_INCREMENT,
  `auction_name`           VARCHAR(255)     DEFAULT '',
  `auction_info`           TEXT             DEFAULT '',
  `check_in_date`          INT(11)          DEFAULT '0',
  `check_out_date`         INT(11)          DEFAULT '0',
  `apply_start_date`       INT(11)          DEFAULT '0',
  `apply_end_date`         INT(11)          DEFAULT '0',
  `auction_price`          DECIMAL(10, 2)   DEFAULT '0.00',
  `auction_low_price`      DECIMAL(10, 2)   DEFAULT '0.00',
  `auction_add_price`      DECIMAL(10, 2)   DEFAULT '0.00',
  `auction_can_use_coupon` TINYINT(1)       DEFAULT '0',
  `auction_sented_coupon`  DECIMAL(10, 2)   DEFAULT '0.00',
  `auction_status`         TINYINT(1)       DEFAULT '0',
  `auction_add_time`       INT(11)          DEFAULT '0',
  PRIMARY KEY (`auction_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = FIXED
  AUTO_INCREMENT = 1;


CREATE TABLE `xmartin_auction_bid` (
  `bid_id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT
  COMMENT '出价ID',
  `auction_id`     INT(11)          NOT NULL,
  `uid`            INT(11)          NOT NULL,
  `bid_price`      DECIMAL(11, 2)   NOT NULL
  COMMENT '出价',
  `bid_time`       INT(11)          NOT NULL,
  `check_in_time`  INT(11)          NOT NULL,
  `check_out_time` INT(11)          NOT NULL,
  `bid_count`      INT(11)          NOT NULL,
  `bid_status`     TINYINT(1)       NOT NULL,
  PRIMARY KEY (`bid_id`),
  KEY `auction_id` (`auction_id`),
  KEY `uid` (`uid`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COMMENT = '出价'
  AUTO_INCREMENT = 1;

-- --------------------------------------------------------

--
-- 表的结构 `xmartin_auction_room`
--

CREATE TABLE `xmartin_auction_room` (
  `auction_id` INT(11) NOT NULL DEFAULT '0',
  `room_id`    INT(11) NOT NULL DEFAULT '0',
  `room_count` INT(11)          DEFAULT '0',
  PRIMARY KEY (`auction_id`, `room_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = FIXED
  COMMENT = '竞拍房间关联';

-- --------------------------------------------------------

--
-- 表的结构 `xmartin_group`
--

CREATE TABLE `xmartin_group` (
  `group_id`             INT(11) NOT NULL AUTO_INCREMENT,
  `group_name`           VARCHAR(255)     DEFAULT '',
  `group_info`           TEXT             DEFAULT '',
  `check_in_date`        INT(11)          DEFAULT '0',
  `check_out_date`       INT(11)          DEFAULT '0',
  `apply_start_date`     INT(11)          DEFAULT '0',
  `apply_end_date`       INT(11)          DEFAULT '0',
  `group_price`          DECIMAL(10, 2)   DEFAULT '0.00',
  `group_can_use_coupon` TINYINT(1)       DEFAULT '0',
  `group_sented_coupon`  DECIMAL(10, 2)   DEFAULT '0.00',
  `group_status`         TINYINT(1)       DEFAULT '0',
  `group_add_time`       INT(11)          DEFAULT '0',
  PRIMARY KEY (`group_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = FIXED
  COMMENT = '团购'
  AUTO_INCREMENT = 1;

-- --------------------------------------------------------

CREATE TABLE `xmartin_group_join` (
  `join_id`     INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id`    INT(11)          NOT NULL,
  `uid`         INT(11)          NOT NULL,
  `room_number` INT(11)          NOT NULL,
  `join_time`   INT(11)          NOT NULL,
  PRIMARY KEY (`join_id`),
  KEY `group_id` (`group_id`),
  KEY `uid` (`uid`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COMMENT = '参加团购'
  AUTO_INCREMENT = 1;

--
-- 表的结构 `xmartin_group_room`
--

CREATE TABLE `xmartin_group_room` (
  `group_id`   INT(11) NOT NULL DEFAULT '0',
  `room_id`    INT(11) NOT NULL DEFAULT '0',
  `room_count` INT(11)          DEFAULT '0',
  PRIMARY KEY (`group_id`, `room_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = FIXED
  COMMENT = '团购房间关联';

-- --------------------------------------------------------

--
-- 表的结构 `xmartin_hotel`
--

CREATE TABLE `xmartin_hotel` (
  `hotel_id`             INT(11)       NOT NULL AUTO_INCREMENT,
  `hotel_city`           INT(11)       NOT NULL
  COMMENT '酒店所属城市',
  `hotel_city_id`        VARCHAR(255)  NULL     DEFAULT '0',
  `hotel_environment`    VARCHAR(255)           DEFAULT '',
  `hotel_rank`           INT(11)                DEFAULT '0'
  COMMENT '酒店排序',
  `hotel_name`           VARCHAR(255)           DEFAULT '0'
  COMMENT '酒店排序',
  `hotel_enname`         VARCHAR(255)           DEFAULT NULL
  COMMENT '酒店排序',
  `hotel_alias`          VARCHAR(255)           DEFAULT NULL
  COMMENT '酒店排序',
  `hotel_keywords`       VARCHAR(255)           DEFAULT NULL
  COMMENT '酒店排序',
  `hotel_tags`           VARCHAR(255)           DEFAULT NULL
  COMMENT '酒店tag',
  `hotel_description`    VARCHAR(255)           DEFAULT NULL,
  `hotel_star`           TINYINT(1)             DEFAULT '0'
  COMMENT '酒店排序',
  `hotel_address`        VARCHAR(255)           DEFAULT NULL,
  `hotel_telephone`      VARCHAR(45)            DEFAULT NULL,
  `hotel_fax`            VARCHAR(45)            DEFAULT NULL,
  `hotel_room_count`     INT(11)                DEFAULT '0',
  `hotel_icon`           VARCHAR(255) COMMENT '酒店图片',
  `hotel_image`          VARCHAR(2000) NULL     DEFAULT NULL,
  `hotel_google`         VARCHAR(255)           DEFAULT NULL,
  `hotel_characteristic` VARCHAR(255)           DEFAULT NULL,
  `hotel_reminded`       VARCHAR(1000)          DEFAULT NULL,
  `hotel_facility`       VARCHAR(1000) NULL     DEFAULT '',
  `hotel_info`           TEXT,
  `hotel_status`         TINYINT(1)             DEFAULT '0',
  `hotel_open_time`      INT(11)                DEFAULT '0',
  `hotel_add_time`       INT(11)                DEFAULT '0',
  PRIMARY KEY (`hotel_id`),
  KEY `hotel_city_id` (`hotel_city_id`),
  KEY `hotel_alias` (`hotel_alias`),
  KEY `hotel_city` (`hotel_city`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = DYNAMIC
  COMMENT = '酒店信息'
  AUTO_INCREMENT = 1;

-- --------------------------------------------------------

--
-- 表的结构 `xmartin_hotel_city`
--

CREATE TABLE `xmartin_hotel_city` (
  `city_id`       INT(11)      NOT NULL AUTO_INCREMENT,
  `city_parentid` INT(11)               DEFAULT '0',
  `city_name`     VARCHAR(45)           DEFAULT NULL,
  `city_alias`    VARCHAR(255) NOT NULL DEFAULT '',
  `city_level`    VARCHAR(45)           DEFAULT NULL,
  PRIMARY KEY (`city_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = DYNAMIC
  AUTO_INCREMENT = 1;

-- --------------------------------------------------------

--
-- 表的结构 `xmartin_hotel_promotions`
--

CREATE TABLE `xmartin_hotel_promotions` (
  `promotion_id`          INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `hotel_id`              INT(11)                   DEFAULT '0',
  `promotion_start_date`  INT(11)                   DEFAULT '0',
  `promotion_end_date`    INT(11)                   DEFAULT '0',
  `promotion_description` TEXT,
  `promotion_add_time`    INT(11)                   DEFAULT '0',
  PRIMARY KEY (`promotion_id`),
  KEY `hotel_id` (`hotel_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = DYNAMIC
  COMMENT = '酒店促销信息';

-- --------------------------------------------------------

--
-- 表的结构 `xmartin_hotel_service`
--

CREATE TABLE `xmartin_hotel_service` (
  `service_id`          INT(11) NOT NULL AUTO_INCREMENT,
  `service_type_id`     INT(11)          DEFAULT '0',
  `service_unit`        VARCHAR(45)      DEFAULT NULL,
  `service_name`        VARCHAR(255)     DEFAULT NULL,
  `service_instruction` VARCHAR(255)     DEFAULT NULL,
  PRIMARY KEY (`service_id`),
  KEY `service_type_id` (`service_type_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = DYNAMIC
  COMMENT = '服务'
  AUTO_INCREMENT = 1;

-- --------------------------------------------------------

--
-- 表的结构 `xmartin_hotel_service_relation`
--

CREATE TABLE `xmartin_hotel_service_relation` (
  `hotel_id`            INT(11)        NOT NULL DEFAULT '0',
  `service_id`          INT(11)        NOT NULL DEFAULT '0',
  `service_extra_price` DECIMAL(11, 2) NULL     DEFAULT '0',
  PRIMARY KEY (`hotel_id`, `service_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = FIXED
  COMMENT = '酒店额外信息';

-- --------------------------------------------------------

--
-- 表的结构 `xmartin_hotel_service_type`
--

CREATE TABLE `xmartin_hotel_service_type` (
  `service_type_id`   INT(11) NOT NULL AUTO_INCREMENT,
  `service_type_name` VARCHAR(255)     DEFAULT NULL,
  PRIMARY KEY (`service_type_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = DYNAMIC
  COMMENT = '服务类目'
  AUTO_INCREMENT = 1;

-- --------------------------------------------------------

--
-- 表的结构 `xmartin_order`
--

CREATE TABLE `xmartin_order` (
  `order_id`            INT(11)     NOT NULL AUTO_INCREMENT,
  `order_type`          TINYINT(1)           DEFAULT '0'  COMMENT 'Booking method',
  `order_mode`          TINYINT(1)           DEFAULT '0'  COMMENT 'Order mode (group purchase, auction)',
  `order_uid`           INT(11)              DEFAULT '0',
  `order_status`        TINYINT(1)           DEFAULT '0'  COMMENT 'Order Status',
  `order_pay_method`    TINYINT(1)           DEFAULT '0',
  `order_pay`           VARCHAR(25) NULL,
  `order_total_price`   DECIMAL(10, 2)       DEFAULT '0.00'  COMMENT '订单状态',
  `order_pay_money`     DECIMAL(10, 2)       DEFAULT '0.00',
  `order_coupon`        DECIMAL(10, 2)       DEFAULT '0.00',
  `order_sented_coupon` DECIMAL(10, 2)       DEFAULT '0.00',
  `order_real_name`     VARCHAR(45)          DEFAULT NULL,
  `order_document_type` TINYINT(1)           DEFAULT '0'  COMMENT '证件类型',
  `order_document`      VARCHAR(255)         DEFAULT NULL  COMMENT '证件',
  `order_telephone`     VARCHAR(45)          DEFAULT NULL,
  `order_phone`         VARCHAR(45)          DEFAULT '0',
  `order_extra_persons` VARCHAR(500)         DEFAULT NULL,
  `order_note`          VARCHAR(255)         DEFAULT NULL,
  `order_status_time`   INT(11)              DEFAULT '0',
  `order_submit_time`   INT(11)              DEFAULT '0',
  PRIMARY KEY (`order_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = DYNAMIC
  COMMENT = '订单'
  AUTO_INCREMENT = 1;

-- --------------------------------------------------------

--
-- 表的结构 `xmartin_order_room`
--

CREATE TABLE `xmartin_order_room` (
  `order_id`   INT(11) NOT NULL DEFAULT '0',
  `room_id`    INT(11) NOT NULL DEFAULT '0',
  `room_date`  INT(11)          DEFAULT '0',
  `room_count` INT(11)          DEFAULT '0',
  KEY (`room_id`),
  KEY (`order_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = FIXED
  COMMENT = '订单房间';

-- --------------------------------------------------------

CREATE TABLE `xmartin_order_query_room` (
  `order_id`   INT(11) NOT NULL AUTO_INCREMENT,
  `room_id`    INT(11)          DEFAULT NULL,
  `room_date`  INT(11)          DEFAULT NULL,
  `room_count` INT(11)          DEFAULT NULL,
  `room_price` DECIMAL(10, 2)   DEFAULT '0.00',
  KEY (`order_id`),
  KEY `room_id` (`room_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  COMMENT = '查询预定'
  AUTO_INCREMENT = 1;

--
-- 表的结构 `xmartin_room`
--

CREATE TABLE `xmartin_room` (
  `room_id`            INT(11)    NOT NULL AUTO_INCREMENT,
  `hotel_id`           INT(11)             DEFAULT '0',
  `room_count`         INT(11)    NOT NULL DEFAULT '0'  COMMENT '客房数量',
  `room_type_id`       INT(11)             DEFAULT '0',
  `room_bed_type`      TINYINT(1) NOT NULL DEFAULT '0'  COMMENT '客房床型',
  `room_name`          VARCHAR(45)         DEFAULT NULL,
  `room_area`          INT(11)             DEFAULT '0',
  `room_floor`         VARCHAR(45)         DEFAULT NULL,
  `room_initial_price` DECIMAL(10, 2)      DEFAULT '0.00',
  `room_is_add_bed`    TINYINT(1)          DEFAULT '0',
  `room_add_money`     INT(11)             DEFAULT '0',
  `room_bed_info`      VARCHAR(255)        DEFAULT NULL,
  `room_status`        TINYINT(1)          DEFAULT '0',
  `room_sented_coupon` DECIMAL(10, 2)      DEFAULT '0.00'
  COMMENT '_AM_XMARTIN_BUY_PRICE',
  PRIMARY KEY (`room_id`),
  KEY `hotel_id` (`hotel_id`),
  KEY `room_type_id` (`room_type_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = DYNAMIC
  COMMENT = '房间信息'
  AUTO_INCREMENT = 1;

-- --------------------------------------------------------

--
-- 表的结构 `xmartin_room_price`
--

CREATE TABLE `xmartin_room_price` (
  `room_id`                   INT(11)        DEFAULT '0',
  `room_is_today_special`     TINYINT(1)     DEFAULT '0',
  `room_price`                DECIMAL(10, 2) DEFAULT '0.00',
  `room_advisory_range_small` DECIMAL(10, 2) DEFAULT '0.00',
  `room_advisory_range_max`   DECIMAL(10, 2) DEFAULT '0.00',
  `room_sented_coupon`        DECIMAL(11, 2) DEFAULT '0.00',
  `room_date`                 INT(11)        DEFAULT '0',
  KEY `room_id` (`room_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = FIXED
  COMMENT = '房间价格';

-- --------------------------------------------------------

--
-- 表的结构 `xmartin_room_type`
--

CREATE TABLE `xmartin_room_type` (
  `room_type_id`   INT(11) NOT NULL AUTO_INCREMENT,
  `room_type_info` VARCHAR(45)      DEFAULT NULL,
  PRIMARY KEY (`room_type_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = DYNAMIC
  COMMENT = '房型'
  AUTO_INCREMENT = 1;


CREATE TABLE `xmartin_order_service` (
  `order_id`      INT(11) NOT NULL DEFAULT '0',
  `service_id`    INT(11) NOT NULL DEFAULT '0',
  `service_date`  INT(11)          DEFAULT '0',
  `service_count` INT(11)          DEFAULT '0',
  KEY `service_id` (`service_id`),
  KEY `order_id` (`order_id`)
)
  ENGINE = MyISAM
  DEFAULT CHARSET = utf8
  ROW_FORMAT = FIXED
  COMMENT = '订单服务';
