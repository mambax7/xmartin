<?php

namespace XoopsModules\Xmartin;

/**
 * Module:martin
 * Licence: GNU
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/xmartin/include/common.php';

/**
 * @method: groupHandler
 * @license   http://www.blags.org/
 * @created   :2010年05月21日 20时40分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
class GroupHandler extends \XoopsPersistableObjectHandler
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
        parent::__construct($db, 'xmartin_group', Group::class, 'group_id', 'group_name');
    }


    /**
     * create a new hotel city
     * @param bool $isNew flag the new objects as "new"?
     * @return object group
     */
    public function &create($isNew = true)
    {
        $group = new Group();
        if ($isNew) {
            $group->setNew();
        }

        return $group;
    }

    /**
     * retrieve a hotel city
     *
     * @param int        $id groupid of the group
     * @param null|mixed $fields
     * @return mixed reference to the {@link group} object, FALSE if failed
     */
    public function get($id = null, $fields = null)
    {
        if ((int)$id <= 0) {
            return false;
        }

        $criteria = new \CriteriaCompo(new \Criteria('group_id', $id));
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
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param int    $limit
     * @param int    $start
     * @param string $sort
     * @param string $order
     * @param bool   $id_as_key
     * @return array
     */
    public function &getGroups($limit = 0, $start = 0, $sort = 'group_add_time', $order = 'DESC', $id_as_key = true)
    {
        $criteria = new \CriteriaCompo();

        $criteria->setSort($sort);
        $criteria->setOrder($order);

        $criteria->setStart($start);
        $criteria->setLimit($limit);

        return $this->getObjects($criteria, $id_as_key);
    }

    /**
     * insert a new group in the database
     *
     * @param object|\XoopsObject $group reference to the {@link group}
     *                                   object
     * @param bool                $force
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(\XoopsObject $group, $force = false)
    {
        $moduleDirName = basename(dirname(__DIR__));
        if ("xoopsmodules\\$moduleDirName\\group" !== mb_strtolower(get_class($group))) {
            return false;
        }

        if (!$group->cleanVars()) {
            return false;
        }

        foreach ($group->cleanVars as $k => $v) {
            ${$k} = $v;
        }

        if ($group->isNew()) {
            $sql = sprintf(
                'INSERT INTO `%s` (
                                group_id,
                                group_name,
                                group_info,
                                check_in_date,
                                check_out_date,
                                apply_start_date,
                                apply_end_date,
                                group_price,
                                group_can_use_coupon,
                                group_sented_coupon,
                                group_status,
                                group_add_time
                            ) VALUES (
                                NULL,
                                %s,%s,%u,%u,%u,%u,%u,%u,%u,%u,%u
                            )',
                $this->db->prefix('xmartin_group'),
                $this->db->quoteString($group_name),
                $this->db->quoteString($group_info),
                $check_in_date,
                $check_out_date,
                $apply_start_date,
                $apply_end_date,
                $group_price,
                $group_can_use_coupon,
                $group_sented_coupon,
                $group_status,
                $group_add_time
            );
        } else {
            $sql = sprintf(
                'UPDATE `%s` SET
                                group_name = %s,
                                group_info = %s,
                                check_in_date = %u,
                                check_out_date = %u,
                                apply_start_date = %u,
                                apply_end_date = %u,
                                group_price = %u,
                                group_can_use_coupon = %u,
                                group_sented_coupon = %u,
                                group_status = %u
                            WHERE group_id = %u',
                $this->db->prefix('xmartin_group'),
                $this->db->quoteString($group_name),
                $this->db->quoteString($group_info),
                $check_in_date,
                $check_out_date,
                $apply_start_date,
                $apply_end_date,
                $group_price,
                $group_can_use_coupon,
                $group_sented_coupon,
                $group_status,
                $group_id
            );
        }
        //echo $sql;exit;
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }

        return $group_id > 0 ? $group_id : $this->db->getInsertId();
    }

    /**
     * @删除一个城市
     * @method:delete(group_id)
     * @license   http://www.blags.org/
     * @created   :2010年05月21日 20时40分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param object|\XoopsObject $group
     * @param bool                $force
     * @return bool|void
     */
    public function delete(\XoopsObject $group, $force = false)
    {
        $moduleDirName = basename(dirname(__DIR__));
        if ("xoopsmodules\\$moduleDirName\\group" !== mb_strtolower(get_class($group))) {
            return false;
        }

        $sql = 'DELETE FROM ' . $this->db->prefix('xmartin_group') . ' WHERE group_id = ' . $group->group_id();

        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }

        $sql = 'DELETE FROM ' . $this->db->prefix('xmartin_group_room') . ' WHERE group_id = ' . $group->group_id();

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
        $sql = 'DELETE FROM ' . $this->db->prefix('xmartin_group');
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
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('xmartin_group');
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
     * @copyright 1997-2010 The Martin Group
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
        $sql   = 'SELECT * FROM ' . $this->db->prefix('xmartin_group');
        if (isset($criteria) && $criteria instanceof \CriteriaElement) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' != $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $sql .= ' order by  apply_start_date DESC , group_id DESC ';
        //echo "<br>" . $sql . "<br>";
        $result = $this->db->query($sql, $limit, $start);

        if (!$result) {
            return $ret;
        }

        $theObjects = [];

        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $group = new Group();
            $group->assignVars($myrow);
            $theObjects[$myrow['group_id']] = &$group;
            //var_dump($group);
            unset($group);
        }
        //var_dump($theObjects);

        foreach ($theObjects as $theObject) {
            if (!$id_as_key) {
                $ret[] = &$theObject;
            } else {
                $ret[$theObject->group_id()] = &$theObject;
            }
            unset($theObject);
        }

        return $ret;
    }

    /**
     * @get       room list
     * @license   http://www.blags.org/
     * @created   :2010年06月03日 20时05分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $group_id
     * @return array|bool
     */
    public function getRoomList($group_id)
    {
        global $xoopsDB;
        if (empty($group_id)) {
            return false;
        }
        $sql    = 'SELECT gr.room_id,gr.room_count,r.room_name FROM ' . $xoopsDB->prefix('xmartin_group_room') . ' gr
            LEFT JOIN ' . $xoopsDB->prefix('xmartin_room') . ' r ON r.room_id = gr.room_id
            WHERE group_id = ' . $group_id;
        $result = $xoopsDB->query($sql);
        $rows   = [];
        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * @param $group_id
     * @param $room_ids
     * @param $room_counts
     * @param $isNew
     * @return bool
     */
    public function InsertGroupRoom($group_id, $room_ids, $room_counts, $isNew)
    {
        global $xoopsDB;
        if (!$group_id || !is_array($room_ids)) {
            // delete data
            $sql = 'DELETE FROM ' . $xoopsDB->prefix('xmartin_group') . ' WHERE group_id = ' . $group_id;
            if ($group_id > 0) {
                $xoopsDB->query($sql);
            }

            return false;
        }
        $dsql = 'delete FROM ' . $xoopsDB->prefix('xmartin_group_room') . " WHERE group_id = $group_id";
        $xoopsDB->query($dsql);

        $sql = 'INSERT INTO ' . $xoopsDB->prefix('xmartin_group_room') . ' (group_id,room_id,room_count) VALUES ';
        foreach ($room_ids as $key => $room_id) {
            $room_count = $room_counts[$key];
            $sql        .= $prefix . "($group_id,$room_id,$room_count)";
            $prefix     = ',';
        }

        //echo $sql;
        return $xoopsDB->query($sql);
    }

    /**
     * @get       room by hotel
     * @license   http://www.blags.org/
     * @created   :2010年06月03日 20时05分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $hotel_id
     * @return array
     */
    public function getRoomListByHotel($hotel_id)
    {
        global $xoopsDB;
        $sql    = 'SELECT room_id,room_name FROM ' . $xoopsDB->prefix('xmartin_room');
        $sql    .= $hotel_id > 0 ? ' WHERE hotel_id = ' . $hotel_id : ' ';
        $result = $xoopsDB->query($sql);
        $rows   = [];
        while (false !== ($row = $xoopsDB->fetchArray($result))) {
            $rows[$row['room_id']] = $row['room_name'];
        }

        return $rows;
    }

    /**
     * @get       top group list
     * @license   http://www.blags.org/
     * @created   :2010年06月20日 13时09分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param int $limit
     * @return array
     */
    public function getGroupList($limit = 6)
    {
        global $xoopsDB;
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('xmartin_group') . ' WHERE group_status = 1 AND apply_end_date > ' . time() . ' ORDER BY apply_end_date , group_id DESC LIMIT ' . $limit;

        return $this->getRows($sql);
    }

    /**
     * @get       Group rooms
     * @license   http://www.blags.org/
     * @created   :2010年06月20日 13时09分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $group_id
     * @return array
     */
    public function getGroupRooms($group_id)
    {
        global $xoopsDB;
        if (!$group_id) {
            return $group_id;
        }
        $sql = 'SELECT gr.*,r.*,rt.room_type_info,h.* FROM ' . $xoopsDB->prefix('xmartin_group_room') . ' gr ';
        $sql .= ' INNER JOIN ' . $xoopsDB->prefix('xmartin_room') . ' r ON ( r.room_id = gr.room_id ) ';
        $sql .= ' INNER JOIN ' . $xoopsDB->prefix('xmartin_room_type') . ' rt ON ( r.room_type_id = rt.room_type_id ) ';
        $sql .= ' INNER JOIN ' . $xoopsDB->prefix('xmartin_hotel') . ' h ON ( r.hotel_id = h.hotel_id ) ';
        $sql .= ' WHERE gr.group_id = ' . $group_id;

        //echo $sql;
        return $this->getRows($sql);
    }

    /**
     * @add       user join group
     * @method:
     * @license   http://www.blags.org/
     * @created   :2010年06月22日 20时19分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $Data
     * @return int
     */
    public function AddUserGroup($Data)
    {
        global $xoopsDB;
        if (!is_array($Data) || empty($Data)) {
            return $Data;
        }
        $sql = 'INSERT INTO ' . $xoopsDB->prefix('xmartin_group_join') . ' (%s) VALUES (%s) ';
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
     * @get       group join list
     * @method:
     * @license   http://www.blags.org/
     * @created   :2010年06月22日 20时19分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $group_id
     * @return array|bool
     */
    public function getGroupJoinList($group_id)
    {
        if (!$group_id) {
            return false;
        }
        global $xoopsDB;
        $sql = 'SELECT j.*,u.uname FROM ' . $xoopsDB->prefix('xmartin_group_join') . ' j ';
        $sql .= 'INNER JOIN ' . $xoopsDB->prefix('users') . ' u ON (u.uid = j.uid) ';
        $sql .= 'WHERE j.group_id = ' . $group_id . ' ';
        $sql .= 'ORDER BY j.join_id DESC ';

        return $this->getRows($sql);
    }

    /**
     * @check     group join exist
     * @license   http://www.blags.org/
     * @created   :2010年06月22日 20时19分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $Data
     * @return bool
     */
    public function checkJoinExist($Data)
    {
        global $xoopsDB;
        $sql  = ' SELECT * FROM ' . $xoopsDB->prefix('xmartin_group_join') . " WHERE uid = {$Data['uid']}
            AND group_id = {$Data['group_id']} ";
        $rows = $this->getRows($sql);

        return is_array($rows) && !empty($rows);
    }
}
