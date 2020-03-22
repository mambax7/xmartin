<?php

namespace XoopsModules\Xmartin;

/**
 * @
 * @method:
 * @license   http://www.blags.org/
 * @created   :2010年07月03日 21时12分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */

use XoopsModules\Xmartin;

/**
 * @martin    cart handler
 * @method:
 * @license   http://www.blags.org/
 * @created   :2010年07月03日 21时12分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
class CartHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @param \XoopsDatabase $db
     * @param null|\XoopsModules\Xmartin\Helper           $helper
     */
//    public function __construct(\XoopsDatabase $db = null, \XoopsModules\Xmartin\Helper $helper = null)
//    {
//        /** @var \XoopsModules\Xmartin\Helper $this->helper */
//        if (null === $helper) {
//            $this->helper = \XoopsModules\Xmartin\Helper::getInstance();
//        } else {
//            $this->helper = $helper;
//        }
//        $isAdmin = $this->helper->isUserAdmin();
//        parent::__construct($db, 'xmartin_order', Order::class, 'order_id', 'order_name');
//    }

    /**
     * @create    cart object
     * @license   http://www.blags.org/
     * @created   :2010年07月04日 12时59分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * */
    public function &create()
    {
        $obj = new Cart();

        return $obj;
    }

    /**
     * @save      cart
     * @license   http://www.blags.org/
     * @created   :2010年07月04日 12时59分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param       $cart
     * @param bool  $force
     * @return bool
     */
    public function saveCart($cart, $force = false)
    {
        $moduleDirName = basename(dirname(__DIR__));
        if ("xoopsmodules\\$moduleDirName\\cart" !== mb_strtolower(get_class($cart))) {
            return false;
        }

        if (!$cart->cleanVars()) {
            return false;
        }

        foreach ($cart->cleanVars as $k => $v) {
            ${$k} = $v;
        }

        $sql = sprintf(
            'INSERT INTO `%s` (
                            order_id,
                            order_type,
                            order_mode,
                            order_uid,
                            order_status,
                            order_pay_method,
                            order_pay,
                            order_total_price,
                            order_pay_money,
                            order_coupon,
                            order_sented_coupon,
                            order_real_name,
                            order_document_type,
                            order_document,
                            order_telephone,
                            order_phone,
                            order_extra_persons,
                            order_note,
                            order_status_time,
                            order_submit_time
                        ) VALUES (
                            NULL,
                            %u,
                            %u,
                            %u,
                            %s,
                            %u,
                            %u,
                            %u,
                            %u,
                            %u,
                            %u,
                            %s,
                            %u,
                            %s,
                            %s,
                            %s,
                            %s,
                            %s,
                            %u,
                            %u
                        )',
            $this->db->prefix('xmartin_order'),
            $order_type,
            $order_mode,
            $order_uid,
            $order_status,
            $order_pay_method,
            $order_pay,
            $order_total_price,
            $order_pay_money,
            $order_coupon,
            $order_sented_coupon,
            $this->db->quoteString($order_real_name),
            $order_document_type,
            $this->db->quoteString($order_document),
            $this->db->quoteString($order_telephone),
            $this->db->quoteString($order_phone),
            $this->db->quoteString($order_extra_persons),
            $this->db->quoteString($order_note),
            $order_status_time,
            $order_submit_time
        );
        //echo $sql;exit;
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }

        return $this->db->getInsertId();
    }

    /**
     * @change    order pay
     * @method:
     * @license   http://www.blags.org/
     * @created   :2010年07月05日 20时22分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $order_id
     * @param $order_pay_method
     * @param $order_pay
     * @return bool
     */
    public function changeOrderPay($order_id, $order_pay_method, $order_pay)
    {
        global $xoopsDB;
        if (!$order_id || !$order_pay_method || !$order_pay) {
            return false;
        }
        $sql = 'UPDATE ' . $xoopsDB->prefix('xmartin_order') . " SET order_pay_method = $order_pay_method ,order_pay = '$order_pay'
            WHERE order_id = $order_id ";

        return $xoopsDB->query($sql);
    }

    /**
     * @check     order if close
     * @method:
     * @license   http://www.blags.org/
     * @created   :2010年07月05日 22时43分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $order_id
     * @return bool
     */
    public function checkOrderClose($order_id)
    {
        if (!$order_id) {
            return false;
        }
        global $xoopsDB;
        $order_status = getModuleArray('order_status', 'order_status', true);
        $sql          = 'SELECT * FROM ' . $xoopsDB->prefix('xmartin_order') . " WHERE order_id = $order_id AND order_status > 6 "; //"= ".count($order_status);
        $row          = $xoopsDB->fetchRow($xoopsDB->query($sql));

        return is_array($row);
    }

    /**
     * @Insert    order room
     * @license   http://www.blags.org/
     * @created   :2010年07月04日 12时59分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $order_id
     * @param $room_id
     * @param $room_date_count
     * @return bool
     */
    public function InsertOrderRoom($order_id, $room_id, $room_date_count)
    {
        global $xoopsDB;
        $result = true;
        if (!$order_id || !$room_id || !$room_date_count) {
            return false;
        }
        $sql = 'INSERT INTO ' . $xoopsDB->prefix('xmartin_order_room') . ' (order_id,room_id,room_date,room_count) VALUES ';
        if (is_array($room_id)) {
            foreach ($room_id as $key => $id) {
                $prefix = '';
                foreach ($room_date_count as $k => $v) {
                    $sql    .= $prefix . "($order_id,$id,$k,$v)";
                    $prefix = ',';
                }
                //echo $sql;exit;
                if (!$xoopsDB->queryF($sql)) {
                    $result = false;
                }
            }
        }

        return $result;
    }

    /**
     * @insert    order service
     * @method:
     * @license   http://www.blags.org/
     * @created   :2010年07月04日 12时59分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $order_id
     * @param $service_date_count
     * @return bool
     */
    public function InsertOrderService($order_id, $service_date_count)
    {
        global $xoopsDB;
        $result = true;
        if (!$order_id || !$service_date_count) {
            return false;
        }
        $sql = 'INSERT INTO ' . $xoopsDB->prefix('xmartin_order_service') . ' (order_id,service_id,service_date,service_count) VALUES ';
        if (is_array($service_date_count)) {
            $prefix = '';
            foreach ($service_date_count as $k => $v) {
                $sql    .= $prefix . "($order_id,$k,0,$v)";
                $prefix = ',';
            }
            //echo $sql;exit;
            if (!$xoopsDB->queryF($sql)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * @get       order info
     * @method:
     * @license   http://www.blags.org/
     * @created   :2010年07月05日 20时22分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $order_id
     * @return array|false
     */
    public function getOrderInfo($order_id)
    {
        if (!$order_id || !is_int($order_id)) {
            return $order_id;
        }
        global $xoopsDB;
        /** @var Xmartin\Helper $helper */
        $helper = Xmartin\Helper::getInstance();

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('xmartin_order') . ' WHERE order_id = ' . $order_id;
        $row = $xoopsDB->fetchArray($xoopsDB->query($sql));
        if (empty($row)) {
            return $row;
        }
        $order_pay_method        = getModuleArray('order_pay_method', 'order_pay_method', true);
        $row['order_pay_method'] = (int)$row['order_pay_method'];
        $pays                    = 2 == $row['order_pay_method'] ? getModuleArray('line_pays', 'line_pays', true) : getModuleArray('online_pays', 'online_pays', true);
        //var_dump($pays);
        $row['order_pay_str']    = isset($pays[$row['order_pay']]) ? $pays[$row['order_pay']] : null;
        $row['order_pay_method'] = isset($order_pay_method['order_pay_method']) ? $order_pay_method['order_pay_method'] : null;
        //var_dump($row);
        $sql      = 'SELECT r.room_name,r.room_bed_info,h.hotel_name,h.hotel_alias FROM ' . $xoopsDB->prefix('xmartin_room') . ' r
            INNER JOIN ' . $xoopsDB->prefix('xmartin_hotel') . ' h ON (r.hotel_id = h.hotel_id)
            INNER JOIN ' . $xoopsDB->prefix('xmartin_order_room') . " mor ON (r.room_id = mor.room_id) WHERE mor.order_id = $order_id LIMIT 1";
        $row_room = $xoopsDB->fetchArray($xoopsDB->query($sql));

        $row['room_name']  = $row_room['hotel_name'] . '-' . $row_room['room_name'];
        $row['hotel_name'] = $row_room['hotel_name'];
        $row['room_info']  = $row_room['room_bed_info'];
        $row['room_url']   = XOOPS_URL . '/hotel-' . $row_room['hotel_alias'] . $helper->getConfig('hotel_static_prefix');
        unset($row_room);

        return $row;
    }

    /**
     * update order status
     * @access    public
     * @param $order_id
     * @param $order_status
     * @return bool
     * @copyright 1997-2010 The Lap Group
     * @author    Martin <china.codehome@gmail.com>
     * @created   time :2010-07-06 16:57:45
     */
    public function UpdateOrderStatus($order_id, $order_status)
    {
        if ($order_status > 0 && $order_id > 0) {
            global $xoopsDB;
            $sql = 'UPDATE ' . $xoopsDB->prefix('xmartin_order') . " SET order_status = $order_status WHERE order_id = " . $order_id;

            return $xoopsDB->queryF($sql);
        }

        return false;
    }
}
