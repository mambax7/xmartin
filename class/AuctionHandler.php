<?php

namespace XoopsModules\Xmartin;

/**
 * Module:martin
 * Licence: GNU
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/xmartin/include/common.php';

/**
 * @method: auctionHandler
 * @license   http://www.blags.org/
 * @created   :2010年05月21日 20时40分
 * @copyright 1997-2010 The Martin auction
 * @author    Martin <china.codehome@gmail.com>
 * */
class AuctionHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @var Helper
     */
    public $helper;
    public $isAdmin;

    /**
     * @param \XoopsDatabase $db
     * @param null|\XoopsModules\Xmartin\Helper           $helper
     */
    public function __construct(\XoopsDatabase $db = null, \XoopsModules\Xmartin\Helper $helper = null)
    {
        /** @var \XoopsModules\Xmartin\Helper $this->helper */
        if (null === $helper) {
            $this->helper = \XoopsModules\Xmartin\Helper::getInstance();
        } else {
            $this->helper = $helper;
        }
        $isAdmin = $this->helper->isUserAdmin();
        parent::__construct($db, 'xmartin_auction', Auction::class, 'auction_id', 'auction_name');
    }

    /**
     * create a new hotel city
     * @param bool $isNew flag the new objects as "new"?
     * @return object auction
     */
    public function &create($isNew = true)
    {
        $auction = new Auction();
        if ($isNew) {
            $auction->setNew();
        }

        return $auction;
    }

    /**
     * retrieve a hotel city
     *
     * @param int        $id auctionid of the auction
     * @param null|mixed $fields
     * @return mixed reference to the {@link auction} object, FALSE if failed
     */
    public function get($id = null, $fields = null)
    {
        if ((int)$id <= 0) {
            return false;
        }

        $criteria = new \CriteriaCompo(new \Criteria('auction_id', $id));
        $criteria->setLimit(1);
        $obj_array = $this->getObjects($criteria);
        if (1 != count($obj_array)) {
            $obj = $this->create();

            return $obj;
        }

        return $obj_array[0];
    }

    /**
     * @get       rows
     * @license   http://www.blags.org/
     * @created   :2010年06月20日 13时09分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param        $sql
     * @param null   $key
     * @return array
     */
    public function getRows($sql, $key = null)
    {
        global $xoopsDB;
        $result = $xoopsDB->query($sql);
        $rows   = [];
        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            if (null === $key) {
                $rows[] = $row;
            } else {
                $rows[$row[$key]] = $row;
            }
        }

        return $rows;
    }

    /**
     * @得到列表
     * @method:
     * @license   http://www.blags.org/
     * @created   :2010年05月23日 14时59分
     * @copyright 1997-2010 The Martin auction
     * @author    Martin <china.codehome@gmail.com>
     * @param int    $limit
     * @param int    $start
     * @param string $sort
     * @param string $order
     * @param bool   $id_as_key
     * @return array
     */
    public function &getAuctions(
        $limit = 0,
        $start = 0,
        $sort = 'auction_add_time',
        $order = 'DESC',
        $id_as_key = true
    ) {
        $criteria = new \CriteriaCompo();

        $criteria->setSort($sort);
        $criteria->setOrder($order);

        $criteria->setStart($start);
        $criteria->setLimit($limit);

        return $this->getObjects($criteria, $id_as_key);
    }

    /**
     * insert a new auction in the database
     *
     * @param \XoopsObject $auction reference to the {@link auction} object
     * @param bool         $force
     * @return bool   FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(\XoopsObject $auction, $force = false)
    {
        $moduleDirName = basename(dirname(__DIR__));
        if ("xoopsmodules\\$moduleDirName\\auction" !== mb_strtolower(get_class($auction))) {
            return false;
        }

        if (!$auction->cleanVars()) {
            return false;
        }

        foreach ($auction->cleanVars as $k => $v) {
            ${$k} = $v;
        }

        if ($auction->isNew()) {
            $sql = sprintf(
                'INSERT INTO `%s` (
                                auction_id,
                                auction_name,
                                auction_info,
                                check_in_date,
                                check_out_date,
                                apply_start_date,
                                apply_end_date,
                                auction_price,
                                auction_low_price,
                                auction_add_price,
                                auction_can_use_coupon,
                                auction_sented_coupon,
                                auction_status,
                                auction_add_time
                            ) VALUES (
                                NULL,
                                %s,%s,%u,%u,%u,%u,%u,%u,%u,%u,%u,%u,%u
                            )',
                $this->db->prefix('xmartin_auction'),
                $this->db->quoteString($auction_name),
                $this->db->quoteString($auction_info),
                $check_in_date,
                $check_out_date,
                $apply_start_date,
                $apply_end_date,
                $auction_price,
                $auction_low_price,
                $auction_add_price,
                $auction_can_use_coupon,
                $auction_sented_coupon,
                $auction_status,
                $auction_add_time
            );
        } else {
            $sql = sprintf(
                'UPDATE `%s` SET
                                auction_name = %s,
                                auction_info = %s,
                                check_in_date = %u,
                                check_out_date = %u,
                                apply_start_date = %u,
                                apply_end_date = %u,
                                auction_price = %u,
                                auction_low_price = %u,
                                auction_add_price = %u,
                                auction_can_use_coupon = %u,
                                auction_sented_coupon = %u,
                                auction_status = %u
                            WHERE auction_id = %u',
                $this->db->prefix('xmartin_auction'),
                $this->db->quoteString($auction_name),
                $this->db->quoteString($auction_info),
                $check_in_date,
                $check_out_date,
                $apply_start_date,
                $apply_end_date,
                $auction_price,
                $auction_low_price,
                $auction_add_price,
                $auction_can_use_coupon,
                $auction_sented_coupon,
                $auction_status,
                $auction_id
            );
        }
        //echo $sql;exit;
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }

        return $auction_id > 0 ? $auction_id : $this->db->getInsertId();
    }

    /**
     * @删除一个城市
     * @method:delete(auction_id)
     * @license   http://www.blags.org/
     * @created   :2010年05月21日 20时40分
     * @copyright 1997-2010 The Martin auction
     * @author    Martin <china.codehome@gmail.com>
     * @param \XoopsObject $auction
     * @param bool         $force
     * @return bool|void
     */
    public function delete(\XoopsObject $auction, $force = false)
    {
        $moduleDirName = basename(dirname(__DIR__));
        if ("xoopsmodules\\$moduleDirName\\auction" !== mb_strtolower(get_class($auction))) {
            return false;
        }

        $sql = 'DELETE FROM ' . $this->db->prefix('xmartin_auction') . ' WHERE auction_id = ' . $auction->auction_id();

        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }

        $sql = 'DELETE FROM ' . $this->db->prefix('xmartin_auction_room') . ' WHERE auction_id = ' . $auction->auction_id();

        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }

        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * delete hotel cities matching a set of conditions
     *
     * @param \CriteriaElement $criteria {@link CriteriaElement}
     * @param mixed            $force
     * @param mixed            $asObject
     * @return bool   FALSE if deletion failed
     */
    public function deleteAll(\CriteriaElement $criteria = null, $force = true, $asObject = false)
    {
        $sql = 'DELETE FROM ' . $this->db->prefix('xmartin_auction');
        if (isset($criteria) && $criteria instanceof \CriteriaElement) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * count hotel cities matching a condition
     *
     * @param \CriteriaElement $criteria {@link CriteriaElement} to match
     * @return int    count of categories
     */
    public function getCount(\CriteriaElement $criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('xmartin_auction');
        if (isset($criteria) && $criteria instanceof \CriteriaElement) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list($count) = $this->db->fetchRow($result);

        return $count;
    }

    /**
     * @得到城市
     * @license   http://www.blags.org/
     * @created   :2010年05月21日 20时40分
     * @copyright 1997-2010 The Martin auction
     * @author    Martin <china.codehome@gmail.com>
     * @param \CriteriaElement $criteria
     * @param bool             $id_as_key
     * @param mixed            $as_object
     * @return array
     */
    public function &getObjects(\CriteriaElement $criteria = null, $id_as_key = false, $as_object = true)
    {
        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('xmartin_auction');
        if (isset($criteria) && $criteria instanceof \CriteriaElement) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' != $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $sql .= ' order by  apply_start_date DESC , auction_id DESC ';
        //echo "<br>" . $sql . "<br>";
        $result = $this->db->query($sql, $limit, $start);

        if (!$result) {
            return $ret;
        }

        $theObjects = [];

        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $auction = new Auction();
            $auction->assignVars($myrow);
            $theObjects[$myrow['auction_id']] = &$auction;
            //var_dump($auction);
            unset($auction);
        }
        //var_dump($theObjects);

        foreach ($theObjects as $theObject) {
            if (!$id_as_key) {
                $ret[] = &$theObject;
            } else {
                $ret[$theObject->auction_id()] = &$theObject;
            }
            unset($theObject);
        }

        return $ret;
    }

    /**
     * @get       room list
     * @license   http://www.blags.org/
     * @created   :2010年06月03日 20时05分
     * @copyright 1997-2010 The Martin auction
     * @author    Martin <china.codehome@gmail.com>
     * @param $auction_id
     * @return array|bool
     */
    public function getRoomList($auction_id)
    {
        global $xoopsDB;
        if (empty($auction_id)) {
            return false;
        }
        $sql    = 'SELECT gr.room_id,gr.room_count,r.room_name FROM ' . $xoopsDB->prefix('xmartin_auction_room') . ' gr
            LEFT JOIN ' . $xoopsDB->prefix('xmartin_room') . ' r ON r.room_id = gr.room_id
            WHERE auction_id = ' . $auction_id;
        $result = $xoopsDB->query($sql);
        $rows   = [];
        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * @param $auction_id
     * @param $room_ids
     * @param $room_counts
     * @param $isNew
     * @return bool
     */
    public function InsertAuctionRoom($auction_id, $room_ids, $room_counts, $isNew)
    {
        global $xoopsDB;
        if (!$auction_id || !is_array($room_ids)) {
            // delete data
            $sql = 'DELETE FROM ' . $xoopsDB->prefix('xmartin_auction') . ' WHERE auction_id = ' . $auction_id;
            if ($auction_id > 0) {
                $xoopsDB->query($sql);
            }

            return false;
        }
        $dsql = 'delete FROM ' . $xoopsDB->prefix('xmartin_auction_room') . " WHERE auction_id = $auction_id";
        $xoopsDB->query($dsql);

        $sql = 'INSERT INTO ' . $xoopsDB->prefix('xmartin_auction_room') . ' (auction_id,room_id,room_count) VALUES ';
        foreach ($room_ids as $key => $room_id) {
            $room_count = $room_counts[$key];
            $sql        .= $prefix . "($auction_id,$room_id,$room_count)";
            $prefix     = ',';
        }

        //echo $sql;
        return $xoopsDB->query($sql);
    }

    /**
     * @get       room by hotel
     * @license   http://www.blags.org/
     * @created   :2010年06月03日 20时05分
     * @copyright 1997-2010 The Martin auction
     * @author    Martin <china.codehome@gmail.com>
     * @param $hotel_id
     * @return array
     */
    public function getRoomListByHotel($hotel_id)
    {
        global $xoopsDB;
        $sql    = 'SELECT room_id,room_name FROM ' . $xoopsDB->prefix('xmartin_auction');
        $sql    .= $hotel_id > 0 ? ' WHERE hotel_id = ' . $hotel_id : ' ';
        $result = $xoopsDB->query($sql);
        $rows   = [];
        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            $rows[$row['room_id']] = $row['room_name'];
        }

        return $rows;
    }

    /**
     * @get       top aution list
     * @license   http://www.blags.org/
     * @created   :2010年06月20日 13时09分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param int $limit
     * @return array
     */
    public function getAuctionList($limit = 6)
    {
        global $xoopsDB;
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('xmartin_auction') . ' WHERE auction_status = 1 AND apply_end_date > ' . time() . ' ORDER BY apply_end_date , auction_id DESC LIMIT ' . $limit;

        return $this->getRows($sql);
    }

    /**
     * @get       Auction rooms
     * @license   http://www.blags.org/
     * @created   :2010年06月20日 13时09分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $auction_id
     * @return array
     */
    public function getAuctionRooms($auction_id)
    {
        global $xoopsDB;
        if (!$auction_id) {
            return $auction_id;
        }
        $sql = 'SELECT a.*,r.*,rt.room_type_info,h.* FROM ' . $xoopsDB->prefix('xmartin_auction_room') . ' a ';
        $sql .= ' INNER JOIN ' . $xoopsDB->prefix('xmartin_room') . ' r ON ( r.room_id = a.room_id ) ';
        $sql .= ' INNER JOIN ' . $xoopsDB->prefix('xmartin_room_type') . ' rt ON ( r.room_type_id = rt.room_type_id ) ';
        $sql .= ' INNER JOIN ' . $xoopsDB->prefix('xmartin_hotel') . ' h ON ( r.hotel_id = h.hotel_id ) ';
        $sql .= ' WHERE a.auction_id = ' . $auction_id;

        //echo $sql;
        return $this->getRows($sql);
    }

    /**
     * @add       user auction bid
     * @license   http://www.blags.org/
     * @created   :2010年06月21日 21时40分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $Data
     * @return int
     */
    public function AddUserAuction($Data)
    {
        global $xoopsDB;
        if (!is_array($Data) || empty($Data)) {
            return $Data;
        }
        $sql = 'INSERT INTO ' . $xoopsDB->prefix('xmartin_auction_bid') . ' (%s) VALUES (%s) ';
        foreach ($Data as $key => $value) {
            $keys   .= $prefix . $key;
            $values .= $prefix . $value;
            $prefix = ',';
        }
        $sql = sprintf($sql, $keys, $values);
        //echo $sql;
        $xoopsDB->query($sql);

        return $xoopsDB->getInsertId();
    }

    /**
     * @get       auction bid list
     * @method:
     * @license   http://www.blags.org/
     * @created   :2010年06月21日 21时40分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $auction_id
     * @return array|bool
     */
    public function getAuctionBidList($auction_id)
    {
        if (!$auction_id) {
            return false;
        }
        global $xoopsDB;
        $sql = 'SELECT b.*,u.uname FROM ' . $xoopsDB->prefix('xmartin_auction_bid') . ' b ';
        $sql .= 'INNER JOIN ' . $xoopsDB->prefix('users') . ' u ON (u.uid = b.uid) ';
        $sql .= 'WHERE b.auction_id = ' . $auction_id . ' ';
        $sql .= 'ORDER BY b.bid_price DESC , b.bid_id DESC ';

        return $this->getRows($sql);
    }
}
