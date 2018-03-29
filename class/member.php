<?php

/**
 * @处理用户中心
 * @license   http://www.blags.org/
 * @created   :2010年07月14日 21时54分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */

use XoopsModules\Xmartin;
/** @var Xmartin\Helper $helper */
$helper = Xmartin\Helper::getInstance();

class MartinMember extends XoopsObject
{
}

/**
 * @处理用户中心
 * @method:
 * @license   http://www.blags.org/
 * @created   :2010年07月14日 21时54分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
class MartinMemberHandler extends XoopsObjectHandler
{
    /**
     * @create    cart object
     * @license   http://www.blags.org/
     * @created   :2010年07月04日 12时59分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * */
    public function &create()
    {
        $obj = newMartinMember;

        return $obj;
    }

    /**
     * @get       rows
     * @license   http://www.blags.org/
     * @created   :2010年06月20日 13时09分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param        $sql
     * @param  null  $key
     * @return array
     */
    public function getRows($sql, $key = null)
    {
        global $xoopsDB;
        $result = $xoopsDB->query($sql);
        $rows   = [];
        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            if (is_null($key)) {
                $rows[] = $row;
            } else {
                $rows[$row[$key]] = $row;
            }
        }

        return $rows;
    }

    /**
     * @add       memeber favorite hotel
     * @method:
     * @license   http://www.blags.org/
     * @created   :2010年07月18日 12时16分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $uid
     * @param $hotel_id
     * @return
     */
    public function addFavorite($uid, $hotel_id)
    {
        $sql = "INSERT INTO {$this->db->prefix('martin_user_favorite')} (uid,hotel_id) VALUES ($uid,$hotel_id)";

        return $this->db->query($sql);
    }

    /**
     * @
     * @method:
     * @license   http://www.blags.org/
     * @created   :2010年07月18日 12时16分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param        $start
     * @param  bool  $isLived
     * @return array
     */
    public function getHotelList($start, $isLived = false)
    {
        $rows = [];
        global $xoopsUser, $xoopsDB,  $searchHandler, $hotelHandler;
        /** @var Xmartin\Helper $helper */
        $helper = Xmartin\Helper::getInstance();

        $uid = $xoopsUser->uid();
        if ($isLived) {
            $order_table = $xoopsDB->prefix('martin_order');
            $sql_str     = "SELECT r.hotel_id FROM {$xoopsDB->prefix('martin_room')} r WHERE r.room_id IN
                (
                 SELECT oqr.room_id FROM {$xoopsDB->prefix('martin_order_query_room')} oqr
                 WHERE order_id IN
                 (SELECT o.order_id FROM $order_table o WHERE o.order_uid = $uid AND o.order_status = 14)
                 UNION
                 SELECT mor.room_id FROM {$xoopsDB->prefix('martin_order_room')} mor
                 WHERE order_id IN
                 (SELECT o.order_id FROM $order_table o WHERE o.order_uid = $uid AND o.order_status = 14)
                )";
        } else {
            $sql_str = "SELECT f.hotel_id FROM {$xoopsDB->prefix('martin_user_favorite')} f WHERE f.uid = $uid";
        }

        //总数
        $sql_count = str_replace('r.hotel_id', 'count(*) as count', $sql_str);
        $sql_count = str_replace('f.hotel_id', 'count(*) as count', $sql_count);
        list($rows['count']) = $xoopsDB->fetchRow($xoopsDB->query($sql_count));

        $sql = "SELECT h.hotel_id,h.hotel_alias,h.hotel_city,h.hotel_name,h.hotel_city_id
                FROM {$xoopsDB->prefix('martin_hotel')} h WHERE h.hotel_id IN (%s) ";
        $sql .= 'GROUP BY hotel_id ORDER BY h.hotel_rank ASC ';
        $sql .= "LIMIT $start,{$helper->getConfig('front_perpage')}";
        $sql = sprintf($sql, $sql_str);
        //echo $sql;
        $hotels     = $this->getRows($sql, 'hotel_id');
        $hotelAlias = $searchHandler->GetCityAlias();
        $cityList   = $hotelHandler->getCityList();
        if (is_array($hotels)) {
            foreach ($hotels as $key => $value) {
                $city_ids = explode(',', $value['hotel_city_id']);
                foreach ($city_ids as $id) {
                    $city_name[] = $cityList[$id];
                }
                $value['hotel_city_id']    = implode('、', $city_name);
                $value['hotel_city_alias'] = XOOPS_URL . '/hotel/' . $hotelAlias[$value['hotel_city']];
                $value['url']              = XOOPS_URL . '/hotel/' . $hotelAlias[$value['hotel_city']] . '/' . $value['hotel_alias'] . $helper->getConfig('hotel_static_prefix');
                $value['hotel_city']       = $cityList[$value['hotel_city']];
                $rows[]                    = $value;
                unset($value, $city_name);
            }
        }
        unset($hotelAlias, $cityList);

        return $rows;
    }

    /**
     * @
     * @method:
     * @license   http://www.blags.org/
     * @created   :2010年07月18日 12时16分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $start
     * @return array
     */
    public function getCouponList($start)
    {
        $rows = [];
        global $xoopsUser, $xoopsDB,  $searchHandler, $hotelHandler;
        /** @var Xmartin\Helper $helper */
        $helper = Xmartin\Helper::getInstance();

        $table = $xoopsDB->prefix('martin_user_coupon');
        $uid   = $xoopsUser->uid();
        $sql   = "SELECT count(*) as count FROM $table WHERE uid = $uid";
        list($rows['count']) = $xoopsDB->fetchRow($xoopsDB->query($sql_count));

        $sql  = "SELECT c.*,h.hotel_name FROM $table c
                LEFT JOIN {$xoopsDB->prefix('martin_order')} o ON (c.relation_id = o.order_id)
                LEFT JOIN {$xoopsDB->prefix('martin_order_room')} mor ON (o.order_id = mor.order_id)
                LEFT JOIN {$xoopsDB->prefix('martin_room')} r ON (r.room_id = mor.room_id)
                LEFT JOIN {$xoopsDB->prefix('martin_hotel')} h ON (r.hotel_id = h.hotel_id)
             ";
        $sql  .= " WHERE o.order_uid = $uid AND c.coupon_type = 1 ";
        $sql  .= "UNION SELECT ob.*,'注册' FROM $table ob WHERE ob.coupon_type = 2 AND ob.uid = $uid ";
        $sql  .= "LIMIT $start,{$helper->getConfig('front_perpage')}";
        $rows = $this->getRows($sql);

        return $rows;
    }

    /**
     * @get       order hotels
     * @method:
     * @license   http://www.blags.org/
     * @created   :2010年07月19日 20时40分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $order_ids
     * @return array|null
     */
    public function getOrderHotels($order_ids)
    {
        global $xoopsDB;
        if (empty($order_ids)) {
            return null;
        }
        $sql = "SELECT h.hotel_name,h.hotel_alias,h.hotel_city,mor.order_id,rt.room_type_info FROM {$xoopsDB->prefix('martin_hotel')} h ";
        $sql .= "LEFT JOIN {$xoopsDB->prefix('martin_room')} r ON (r.hotel_id = h.hotel_id) ";
        $sql .= "LEFT JOIN {$xoopsDB->prefix('martin_room_type')} rt ON (rt.room_type_id = r.room_type_id) ";
        $sql .= "LEFT JOIN {$xoopsDB->prefix('martin_order_room')} mor ON (mor.room_id = r.room_id) ";
        $sql .= 'WHERE mor.order_id IN  (' . implode(',', $order_ids) . ') GROUP BY mor.order_id ';
        $sql .= "UNION SELECT h.hotel_name,h.hotel_alias,h.hotel_city,oqr.order_id,rt.room_type_info FROM {$xoopsDB->prefix('martin_hotel')} h ";
        $sql .= "LEFT JOIN {$xoopsDB->prefix('martin_room')} r ON (r.hotel_id = h.hotel_id) ";
        $sql .= "LEFT JOIN {$xoopsDB->prefix('martin_room_type')} rt ON (rt.room_type_id = r.room_type_id) ";
        $sql .= "LEFT JOIN {$xoopsDB->prefix('martin_order_query_room')} oqr ON (oqr.room_id = r.room_id) ";
        $sql .= 'WHERE oqr.order_id IN  (' . implode(',', $order_ids) . ') GROUP BY oqr.order_id ';

        return $this->getRows($sql, 'order_id');
    }

    /**
     * @
     * @method:
     * @license   http://www.blags.org/
     * @created   :2010年07月19日 20时40分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $order_id
     * @param $hotel_id
     * @return array
     */
    public function getOrderService($order_id, $hotel_id)
    {
        global $xoopsDB;
        $sql = "SELECT os.*,s.*,st.*,hs.service_extra_price FROM {$xoopsDB->prefix('martin_hotel_service')} s ";
        $sql .= "INNER JOIN {$xoopsDB->prefix('martin_order_service')} os ON (os.service_id = s.service_id) ";
        $sql .= "INNER JOIN {$xoopsDB->prefix('martin_hotel_service_type')} st ON (st.service_type_id = s.service_type_id) ";
        $sql .= "INNER JOIN {$xoopsDB->prefix('martin_hotel_service_relation')} hs ON (s.service_id = hs.service_id) ";
        $sql .= "WHERE os.order_id = $order_id AND hs.hotel_id = $hotel_id ";
        $sql .= 'GROUP BY s.service_id';

        return $this->getRows($sql, 'service_id');
    }
}
