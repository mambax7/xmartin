<?php
/**
 *
 * Module:martin
 * Licence: GNU
 */

defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/martin/include/common.php';

/**
 * Class MartinRoom
 */
class MartinRoom extends \XoopsObject
{
    public function __construct()
    {
        $this->initVar('room_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('hotel_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('room_count', XOBJ_DTYPE_INT, null, false);
        $this->initVar('hotel_name', XOBJ_DTYPE_TXTBOX, null, true, 45);
        $this->initVar('room_type_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('room_bed_type', XOBJ_DTYPE_INT, null, false);
        $this->initVar('room_type_info', XOBJ_DTYPE_TXTBOX, null, true, 45);
        $this->initVar('room_name', XOBJ_DTYPE_TXTBOX, null, true, 45);
        $this->initVar('room_area', XOBJ_DTYPE_INT, null, false);
        $this->initVar('room_floor', XOBJ_DTYPE_TXTBOX, null, true, 45);
        $this->initVar('room_initial_price', XOBJ_DTYPE_INT, null, false);
        $this->initVar('room_is_add_bed', XOBJ_DTYPE_INT, null, false);
        $this->initVar('room_add_money', XOBJ_DTYPE_INT, null, false);
        $this->initVar('room_bed_info', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('room_status', XOBJ_DTYPE_INT, null, false);
        $this->initVar('room_sented_coupon', XOBJ_DTYPE_INT, null, false);
    }

    /**
     * @return mixed
     */
    public function room_id()
    {
        return $this->getVar('room_id');
    }

    /**
     * @return mixed
     */
    public function hotel_id()
    {
        return $this->getVar('hotel_id');
    }

    /**
     * @return mixed
     */
    public function room_count()
    {
        return $this->getVar('room_count');
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function hotel_name($format = 'S')
    {
        return $this->getVar('hotel_name', $format);
    }

    /**
     * @return mixed
     */
    public function room_type_id()
    {
        return $this->getVar('room_type_id');
    }

    /**
     * @return mixed
     */
    public function room_bed_type()
    {
        return $this->getVar('room_bed_type');
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function room_type_info($format = 'S')
    {
        return $this->getVar('room_type_info', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function room_name($format = 'S')
    {
        return $this->getVar('room_name', $format);
    }

    /**
     * @return mixed
     */
    public function room_area()
    {
        return $this->getVar('room_area');
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function room_floor($format = 'S')
    {
        return $this->getVar('room_floor', $format);
    }

    /**
     * @return mixed
     */
    public function room_initial_price()
    {
        return $this->getVar('room_initial_price');
    }

    /**
     * @return mixed
     */
    public function room_is_add_bed()
    {
        return $this->getVar('room_is_add_bed');
    }

    /**
     * @return mixed
     */
    public function room_add_money()
    {
        return $this->getVar('room_add_money');
    }

    /**
     * @return mixed
     */
    public function room_status()
    {
        return $this->getVar('room_status');
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function room_bed_info($format = 'S')
    {
        return $this->getVar('room_bed_info', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function room_sented_coupon($format = 'S')
    {
        return $this->getVar('room_sented_coupon', $format);
    }
}

/**
 * @method: roomHandler
 * @license   http://www.blags.org/
 * @created   :2010年05月21日 20时40分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
class MartinRoomHandler extends \XoopsObjectHandler
{
    /**
     * create a new hotel city
     * @param  bool $isNew flag the new objects as "new"?
     * @return object room
     */
    public function &create($isNew = true)
    {
        $room = new MartinRoom();
        if ($isNew) {
            $room->setNew();
        }

        return $room;
    }

    /**
     * retrieve a hotel city
     *
     * @param  int $id roomid of the room
     * @return mixed reference to the {@link room} object, FALSE if failed
     */
    public function &get($id)
    {
        if ((int)$id <= 0) {
            return false;
        }

        $criteria = new \CriteriaCompo(new \Criteria('room_id', $id));
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
     * @param  null  $key
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
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param  int    $limit
     * @param  int    $start
     * @param  string $sort
     * @param  string $order
     * @param  bool   $id_as_key
     * @return array
     */
    public function &getRooms($limit = 0, $start = 0, $sort = 'room_id', $order = 'ASC', $id_as_key = true)
    {
        $criteria = new \CriteriaCompo();

        $criteria->setSort($sort);
        $criteria->setOrder($order);

        $criteria->setStart($start);
        $criteria->setLimit($limit);

        return $this->getObjects($criteria, $id_as_key);
    }

    /**
     * insert a new room in the database
     *
     * @param object|XoopsObject $room reference to the {@link room}
     *                                 object
     * @param  bool              $force
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(\XoopsObject $room, $force = false)
    {
        if ('martinroom' !== strtolower(get_class($room))) {
            return false;
        }

        if (!$room->cleanVars()) {
            return false;
        }

        foreach ($room->cleanVars as $k => $v) {
            ${$k} = $v;
        }

        if ($room->isNew()) {
            $sql = sprintf(
                'INSERT INTO `%s` (
                                room_id,room_type_id,hotel_id,room_count,room_bed_type,room_name,room_area,
                                room_floor,room_initial_price,room_is_add_bed,
                                room_add_money,room_bed_info,room_status,room_sented_coupon
                            ) VALUES (
                                NULL,%u,%u,%u,%u,%s,%u,
                                %s,%u,%u,
                                %u,%s,%u,%u
                            )',
                $this->db->prefix('martin_room'),
                $room_type_id,
                $hotel_id,
                $room_count,
                $room_bed_type,
                $this->db->quoteString($room_name),
                $room_area,
                $this->db->quoteString($room_floor),
                $room_initial_price,
                $room_is_add_bed,
                $room_add_money,
                           $this->db->quoteString($room_bed_info),
                $room_status,
                $room_sented_coupon
            );
        } else {
            $sql = sprintf(
                'UPDATE `%s` SET
                                room_type_id = %u,
                                hotel_id = %u,
                                room_count = %u,
                                room_bed_type = %u,
                                room_name = %s,
                                room_area = %u,
                                room_floor = %s,
                                room_initial_price = %u,
                                room_is_add_bed = %u,
                                room_add_money = %u,
                                room_bed_info = %s,
                                room_status = %u,
                                room_sented_coupon = %u
                            WHERE room_id = %u',
                $this->db->prefix('martin_room'),
                $room_type_id,
                $hotel_id,
                $room_count,
                $room_bed_type,
                $this->db->quoteString($room_name),
                $room_area,
                $this->db->quoteString($room_floor),
                $room_initial_price,
                $room_is_add_bed,
                $room_add_money,
                           $this->db->quoteString($room_bed_info),
                $room_status,
                $room_sented_coupon,
                $room_id
            );
        }
        //echo $sql;exit;
        //echo "<br>" . $sql . "<br>";
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            $room->setErrors('The query returned an error. ' . $this->db->error());

            return false;
        }
        if ($room->isNew()) {
            $room->assignVar('room_id', $this->db->getInsertId());
        }

        $room->assignVar('room_id', $room_id);

        return true;
    }

    /**
     * check hotel room exist
     * @access    public
     * @param $roomObj
     * @copyright 1997-2010 The Lap Group
     * @author    Martin <china.codehome@gmail.com>
     * @created   time :2010-06-28 11:08:41
     * @return bool
     */
    public function checkHotelRoomExist($roomObj)
    {
        $sql = 'SELECT count(*) AS count FROM ' . $this->db->prefix('martin_room') . ' WHERE room_type_id = ' . $roomObj->room_type_id() . ' ';
        $sql .= 'AND hotel_id = ' . $roomObj->hotel_id();
        list($count) = $this->db->fetchRow($this->db->query($sql));
        if (($roomObj->isNew() && $count > 0) || $count > 1) {
            return true;
        }

        return false;
    }

    /**
     * @删除一个城市
     * @method:delete(room_id)
     * @license   http://www.blags.org/
     * @created   :2010年05月21日 20时40分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param object|XoopsObject $room
     * @param  bool              $force
     * @return bool|void
     */
    public function delete(\XoopsObject $room, $force = false)
    {
        if ('martinroom' !== strtolower(get_class($room))) {
            return false;
        }

        $sql = 'DELETE FROM ' . $this->db->prefix('martin_room') . ' WHERE room_id = ' . $room->room_id();

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
     * count room matching a condition
     *
     * @param  object $criteria {@link CriteriaElement} to match
     * @return int    count of categories
     */
    public function getCount($criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('martin_room');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
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
     * @get       objects
     * @license   http://www.blags.org/
     * @created   :2010年05月21日 20时40分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param  null $criteria
     * @param  bool $id_as_key
     * @return array
     */
    public function &getObjects($criteria = null, $id_as_key = false)
    {
        $ret   = [];
        $limit = $start = 0;

        $sql = 'SELECT r.*,rt.room_type_info,h.hotel_name FROM ' . $this->db->prefix . '_martin_room' . ' r LEFT JOIN ' . $this->db->prefix . '_martin_room_type' . ' rt ON (r.room_type_id = rt.room_type_id ) LEFT JOIN ' . $this->db->prefix . '_martin_hotel' . ' h ON ( h.hotel_id = r.hotel_id ) ';
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' != $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $sql .= ' Group BY r.room_id ';
        //echo "<br>" . $sql . "<br>";
        $result = $this->db->query($sql, $limit, $start);

        if (!$result) {
            return $ret;
        }

        $theObjects = [];

        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $room = new MartinRoom();
            $room->assignVars($myrow);
            $theObjects[$myrow['room_id']] =& $room;
            //var_dump($room);
            unset($room);
        }
        //var_dump($theObjects);

        foreach ($theObjects as $theObject) {
            if (!$id_as_key) {
                $ret[] =& $theObject;
            } else {
                $ret[$theObject->room_id()] =& $theObject;
            }
            unset($theObject);
        }

        return $ret;
    }

    /**
     * @get       room type list
     * @license   http://www.blags.org/
     * @created   :2010年05月31日 20时32分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param  int $room_type_id
     * @return array
     */
    public function getRoomTypeList($room_type_id = 0)
    {
        $rows   = [];
        $sql    = 'SELECT * FROM ' . $this->db->prefix('martin_room_type');
        $sql    .= $room_type_id > 0 ? " WHERE room_type_id = $room_type_id" : '';
        $result = $this->db->query($sql);
        while (false !== ($row = $this->db->fetchArray($result))) {
            $rows[$row['room_type_id']] = $row['room_type_info'];
        }

        return $rows;
    }

    /**
     * @get       room type list
     * @license   http://www.blags.org/
     * @created   :2010年05月31日 20时32分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param  int $room_id
     * @return array
     */
    public function getRoomList($room_id = 0)
    {
        $rows   = [];
        $sql    = 'SELECT room_id,room_name FROM ' . $this->db->prefix('martin_room');
        $sql    .= $room_id > 0 ? " WHERE room_id = $room_id" : '';
        $result = $this->db->query($sql);
        while (false !== ($row = $this->db->fetchArray($result))) {
            $rows[$row['room_id']] = $row['room_name'];
        }

        return $rows;
    }

    /**
     * insert update room type
     * @license   http://www.blags.org/
     * @created   :2010年05月31日 20时32分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $typeData
     * @return bool|\mysqli_result
     */
    public function insertType($typeData)
    {
        global $xoopsDB;
        if (empty($typeData)) {
            return $typeData;
        }
        foreach ($typeData as $key => $value) {
            ${$key} = $value;
        }
        if ($room_type_id > 0) {
            $sql = 'UPDATE ' . $xoopsDB->prefix('martin_room_type') . ' set room_type_info = ' . $xoopsDB->quoteString($room_type_info) . ' WHERE room_type_id = ' . $room_type_id;
        } else {
            $sql = 'insert INTO ' . $xoopsDB->prefix('martin_room_type') . " (room_type_id,room_type_info) VALUES (null,'$room_type_info')";
        }

        //echo $sql;exit;
        return $this->db->queryF($sql);
    }

    /**
     * @delete    room type
     * @license   http://www.blags.org/
     * @created   :2010年05月31日 20时32分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $typeid
     * @return bool|\mysqli_result
     */
    public function deleteRoomType($typeid)
    {
        global $xoopsDB;
        if (!$typeid) {
            return $typeid;
        }
        $sql = 'DELETE FROM ' . $xoopsDB->prefix('martin_room_type') . ' WHERE room_type_id  = ' . $typeid;

        return $xoopsDB->queryF($sql);
    }

    /**
     * @get       room price
     * @license   http://www.blags.org/
     * @created   :2010年05月31日 20时32分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param  int $room_id
     * @param  int $room_date
     * @return array
     */
    public function getRoomPrice($room_id = 0, $room_date = 0)
    {
        $NextMouth = mktime(0, 0, 0, date('m') + 1, date('d'), date('Y'));
        $Today     = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $rows      = [];
        $sql       = 'SELECT * FROM ' . $this->db->prefix('martin_room_price');
        $sql       .= ($room_id > 0) ? ' WHERE room_id = ' . $room_id . ' ' : ' ';
        $sql       .= $room_date > 0 ? 'and room_date = ' . strtotime($room_date) . ' ' : ' and room_date BETWEEN ' . $Today . ' AND ' . $NextMouth;
        $sql       .= ' order by room_id , room_date desc ';
        $result    = $this->db->query($sql);
        while (false !== ($row = $this->db->fetchArray($result))) {
            if ($room_date > 0) {
                return $row;
            }
            $rows[$row['room_date']] = $row;
        }

        return $room_date > 0 ? $rows[0] : $rows;
    }

    /**
     * @get       price list
     * @license   http://www.blags.org/
     * @created   :2010年06月01日 21时45分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param        $limit
     * @param  int   $start
     * @return array
     */
    public function getRoomPriceList($limit, $start = 0)
    {
        $rows   = [];
        $sql    = 'SELECT rp.*,r.room_name FROM ' . $this->db->prefix('martin_room_price');
        $sql    .= ' rp left join ' . $this->db->prefix('martin_room') . ' r ON r.room_id = rp.room_id ';
        $sql    .= ($room_id > 0 && $room_date > 0) ? " WHERE room_id = $room_id and room_date = " . $room_date : '';
        $sql    .= ' order by room_id , room_date desc ';
        $sql    .= " limit $start,$limit ";
        $result = $this->db->query($sql);
        while (false !== ($row = $this->db->fetchArray($result))) {
            $row['room_date'] = date('Y-m-d', $row['room_date']);
            $rows[]           = $row;
        }

        return $rows;
    }

    /**
     * @get       price count
     * @license   http://www.blags.org/
     * @created   :2010年06月01日 21时45分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * */
    public function getRoomPriceCount()
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('martin_room_price');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
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
     * @清空过期数据
     * @license   http://www.blags.org/
     * @created   :2010年06月01日 21时45分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param int $date
     * @return void
     */
    public function truncatePassData($date = 0)
    {
        global $xoopsDB;
        $date = empty($date) ? date('Y-m-d') : $date;
        $date = strtotime($date);
        $sql  = 'DELETE FROM ' . $xoopsDB->prefix('martin_room_price') . ' WHERE room_date < ' . $date;

        //echo $sql;exit;
        return $xoopsDB->query($sql);
    }

    /**
     * @delete    room date price
     * @license   http://www.blags.org/
     * @created   :2010年05月31日 20时32分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $room_id
     * @param $room_date
     * @return bool
     */
    public function deleteRoomPrice($room_id, $room_date)
    {
        global $xoopsDB;
        if (!$room_id || !$room_date) {
            return false;
        }
        $sql = 'DELETE FROM ' . $xoopsDB->prefix('martin_room_price') . ' WHERE room_id  = ' . $room_id . ' AND room_date = ' . strtotime($room_date);

        return $xoopsDB->queryF($sql);
    }

    /**
     * @ insert room price
     * @license   http://www.blags.org/
     * @created   :2010年06月01日 21时45分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $Data
     * @param $IsOld
     * @return bool
     */
    public function InsertRoomPrice($Data, $IsOld)
    {
        //var_dump($Data);exit;
        global $xoopsDB;
        if ($IsOld) {
            $sql = 'UPDATE ' . $xoopsDB->prefix('martin_room_price') . " set
                room_price = {$Data['room_price']},
                room_is_today_special = {$Data['room_is_today_special']},
                room_advisory_range_small = {$Data['room_advisory_range_small']},
                room_advisory_range_max = {$Data['room_advisory_range_max']},
                room_sented_coupon = {$Data['room_sented_coupon']}
                WHERE room_id = {$Data['room_id']} and room_date = {$Data['room_date']}
                ";
        } else {
            $sql    = 'INSERT INTO ' . $xoopsDB->prefix('martin_room_price') . ' (room_id,room_is_today_special,room_price,room_advisory_range_small,room_advisory_range_max,room_sented_coupon,room_date ) VALUES ';
            $Insert = false;
            foreach ($Data as $price) {
                if (!$this->checkExistDate($price['room_id'], $price['room_date'])) {
                    $sql    .= $prefix . "({$price['room_id']},{$price['room_is_today_special']},{$price['room_price']},
                        {$price['room_advisory_range_small']},{$price['room_advisory_range_max']},
                        {$price['room_sented_coupon']},{$price['room_date']})";
                    $prefix = ',';
                    $Insert = true;
                } else {
                    $upSql = 'UPDATE ' . $xoopsDB->prefix('martin_room_price') . " SET
                            room_price = {$price['room_price']} ,
                            room_sented_coupon = {$price['room_sented_coupon']} ,
                            room_advisory_range_small = {$price['room_advisory_range_small']},
                            room_advisory_range_max = {$price['room_advisory_range_max']},
                            room_is_today_special = {$price['room_is_today_special']}
                            WHERE room_id = {$price['room_id']} and room_date = {$price['room_date']}
                            ";
                    //echo $upSql;
                    $xoopsDB->queryF($upSql);
                }
            }

            return $Insert ? $xoopsDB->queryF($sql) : true;
        }

        //echo $sql;exit;
        return $xoopsDB->queryF($sql);
    }

    /**
     * @check     exist
     * @method:
     * @license   http://www.blags.org/
     * @created   :2010年06月24日 22时04分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $room_id
     * @param $room_date
     * @return bool
     */
    public function checkExistDate($room_id, $room_date)
    {
        global $xoopsDB;
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('martin_room_price') . " WHERE room_id = $room_id and room_date = $room_date ";

        return is_array($xoopsDB->fetchArray($xoopsDB->query($sql)));
    }

    /**
     * @get       max add date
     * @license   http://www.blags.org/
     * @created   :2010年06月02日 21时02分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $mouth
     * @return int
     */
    public function getMaxDate($mouth)
    {
        global $xoopsDB;
        $date    = date('Y') . '-' . $mouth . '-01';
        $maxdate = date('Y') . '-' . ($mouth + 1) . '-01';
        $date    = strtotime($date);
        $maxdate = strtotime($maxdate);
        $sql     = 'SELECT max(room_date) FROM ' . $xoopsDB->prefix('martin_room_price') . " WHERE room_date < $maxdate and room_date > $date ";
        $result  = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list($count) = $this->db->fetchRow($result);

        return $count;
    }

    /**
     * @get       hotel room
     * @计算平均值
     * @license   http://www.blags.org/
     * @created   :2010年06月14日 20时47分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $hotel_id
     * @return array
     */
    public function getHotelRoom($hotel_id)
    {
        if (!$hotel_id > 0) {
            return null;
        }
        global $xoopsDB;
        $time            = strtotime(date('Y-m-d'));
        $RoomBedTypeList = getModuleArray('room_bed_type', 'order_type', true);
        $sql             = "SELECT r.*,rt.room_type_info,rp.*,
            GROUP_CONCAT(rp.room_price) as room_prices,GROUP_CONCAT(rp.room_date) as room_dates ,
            GROUP_CONCAT(rp.room_sented_coupon) as room_sented_coupons
            FROM {$xoopsDB->prefix('martin_room')} r
            INNER JOIN {$xoopsDB->prefix('martin_room_type')} rt ON (rt.room_type_id = r.room_type_id) ";
        $sql             .= "INNER JOIN {$xoopsDB->prefix('martin_room_price')} rp ON (r.room_id = rp.room_id) WHERE ";
        $sql             .= $this->check_date ? "rp.room_date BETWEEN {$this->check_date[0]} AND {$this->check_date[1]} " : "rp.room_date = $time ";
        $sql             .= "AND r.hotel_id = $hotel_id AND r.room_status = 1 GROUP BY r.room_id ORDER BY r.room_sented_coupon DESC , r.room_id DESC ";
        //echo $sql;
        $rows   = [];
        $result = $xoopsDB->query($sql);
        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            $room_dates                 = [];
            $room_all_price             = 0;
            $room_all_sended_coupon     = 0;
            $row['room_prices']         = explode(',', $row['room_prices']);
            $row['room_dates']          = explode(',', $row['room_dates']);
            $row['room_sented_coupons'] = explode(',', $row['room_sented_coupons']);
            foreach ($row['room_prices'] as $key => $room_price) {
                $d                  = $row['room_dates'][$key];
                $room_sented_coupon = $row['room_sented_coupons'][$key];
                if ($d >= $this->check_date[0] && $d < $this->check_date[1]) {
                    $room_all_price         += $room_price;
                    $room_all_sended_coupon += $room_sented_coupon;
                    $room_prices[]          = ['date' => date('Y-m-d', $d), 'price' => $room_price];
                }
            }
            unset($row['room_prices'], $row['room_dates'], $row['room_sented_coupons']);
            $row['room_prices'] = $room_prices;
            if ($this->check_date) {
                $row['room_price']         = round($room_all_price / $key, 2);
                $row['room_sented_coupon'] = round($room_all_sended_coupon / $key, 2);
            }
            $row['room_bed_type'] = $RoomBedTypeList[$row['room_bed_type']];
            $rows[]               = $row;
            unset($row, $room_prices);
        }

        return $rows;
    }

    /**
     * @get       room date price
     * @method:
     * @license   http://www.blags.org/
     * @created   :2010年07月01日 22时08分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $room_id
     * @param $check_in_date
     * @param $check_out_date
     * @return array|bool
     */
    public function getRoomDatePrie($room_id, $check_in_date, $check_out_date)
    {
        global $xoopsDB;
        if (!$room_id || !$check_in_date || !$check_out_date) {
            return false;
        }
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('martin_room_price') . " WHERE room_id = $room_id AND room_date BETWEEN $check_in_date AND $check_out_date ";

        return $this->getRows($sql, 'room_date');
    }
}
