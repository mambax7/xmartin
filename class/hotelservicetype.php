<?php
/**
 *
 * Module:martin
 * Licence: GNU
 */

defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/martin/include/common.php';

/**
 * Class MartinHotelServiceType
 */
class MartinHotelServiceType extends XoopsObject
{
    public function __construct()
    {
        $this->initVar('service_type_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('service_type_name', XOBJ_DTYPE_TXTBOX, null, true, 255);
    }

    /**
     * @return mixed
     */
    public function service_type_id()
    {
        return $this->getVar('service_type_id');
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function service_type_name($format = 'S')
    {
        return $this->getVar('service_type_name', $format);
    }
}

/**
 * @method: hotelservicetypeHandler
 * @license   http://www.blags.org/
 * @created   :2010年05月21日 20时40分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
class MartinHotelServiceTypeHandler extends XoopsObjectHandler
{
    /**
     * create a new hotel city
     * @param  bool $isNew flag the new objects as "new"?
     * @return object hotelservicetype
     */
    public function &create($isNew = true)
    {
        $hotelservicetype = new MartinHotelServiceType();
        if ($isNew) {
            $hotelservicetype->setNew();
        }

        return $hotelservicetype;
    }

    /**
     * retrieve a hotel city
     *
     * @param  int $id hotelservicetypeid of the hotelservicetype
     * @return mixed reference to the {@link hotelservicetype} object, FALSE if failed
     */
    public function &get($id)
    {
        if ((int)$id <= 0) {
            return false;
        }

        $criteria = new \CriteriaCompo(new \Criteria('service_type_id', $id));
        $criteria->setLimit(1);
        $obj_array =& $this->getObjects($criteria);
        if (1 != count($obj_array)) {
            $obj = $this->create();

            return $obj;
        }

        return $obj_array[0];
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
    public function &getHotelServiceTypes(
        $limit = 0,
        $start = 0,
        $sort = 'service_type_id',
        $order = 'ASC',
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
     * insert a new hotelservicetype in the database
     *
     * @param object|\XoopsObject $hotelservicetype reference to the {@link hotelservicetype}
     *                                             object
     * @param  bool              $force
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(\XoopsObject $hotelservicetype, $force = false)
    {
        if ('martinhotelservicetype' !== strtolower(get_class($hotelservicetype))) {
            return false;
        }

        if (!$hotelservicetype->cleanVars()) {
            return false;
        }

        foreach ($hotelservicetype->cleanVars as $k => $v) {
            ${$k} = $v;
        }

        if ($hotelservicetype->isNew()) {
            $sql = sprintf('INSERT INTO %s (
                                service_type_id,
                                service_type_name
                            ) VALUES (
                                NULL,
                                %s
                            )', $this->db->prefix('martin_hotel_service_type'), $this->db->quoteString($service_type_name));
        } else {
            $sql = sprintf('UPDATE %s SET
                                service_type_name = %s
                            WHERE service_type_id = %u', $this->db->prefix('martin_hotel_service_type'), $this->db->quoteString($service_type_name), $service_type_id);
        }
        //echo $sql;exit;
        if (false != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            $hotelservicetype->setErrors('The query returned an error. ' . $this->db->error());

            return false;
        }
        if ($hotelservicetype->isNew()) {
            $hotelservicetype->assignVar('service_type_id', $this->db->getInsertId());
        }

        $hotelservicetype->assignVar('service_type_id', $service_id);

        return true;
    }

    /**
     * @删除一个城市
     * @method:delete(service_id)
     * @license   http://www.blags.org/
     * @created   :2010年05月21日 20时40分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param object|\XoopsObject $hotelservicetype
     * @param  bool              $force
     * @return bool|void
     */
    public function delete(\XoopsObject $hotelservicetype, $force = false)
    {
        if ('martinhotelservicetype' !== strtolower(get_class($hotelservicetype))) {
            return false;
        }

        $sql = 'DELETE FROM ' . $this->db->prefix('martin_hotel_service_type') . ' WHERE service_type_id = ' . $hotelservicetype->service_type_id();

        if (false != $force) {
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
     * @param  object $criteria {@link CriteriaElement}
     * @return bool   FALSE if deletion failed
     */
    public function deleteAll($criteria = null)
    {
        $sql = 'DELETE FROM ' . $this->db->prefix('martin_hotel_service_type');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
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
     * @param  object $criteria {@link CriteriaElement} to match
     * @return int    count of categories
     */
    public function getCount($criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('martin_hotel_service_type');
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
     * @得到城市
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
        $sql   = 'SELECT * FROM ' . $this->db->prefix('martin_hotel_service_type');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' != $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        //echo "<br>" . $sql . "<br>";
        $result = $this->db->query($sql, $limit, $start);

        if (!$result) {
            return $ret;
        }

        $theObjects = [];

        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $hotelservicetype = new MartinHotelServiceType();
            $hotelservicetype->assignVars($myrow);
            $theObjects[$myrow['service_type_id']] =& $hotelservicetype;
            //var_dump($hotelservicetype);
            unset($hotelservicetype);
        }
        //var_dump($theObjects);

        foreach ($theObjects as $theObject) {
            if (!$id_as_key) {
                $ret[] =& $theObject;
            } else {
                $ret[$theObject->service_type_id()] =& $theObject;
            }
            unset($theObject);
        }

        return $ret;
    }

    /**
     * @得到类别列表
     * @license   http://www.blags.org/
     * @created   :2010年05月30日 20时48分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * */
    public function getList()
    {
        $sql    = 'SELECT * FROM ' . $this->db->prefix('martin_hotel_service_type');
        $result = $this->db->query($sql);
        $rows   = [];
        while (false !== ($row = $this->db->fetchArray($result))) {
            $rows[$row['service_type_id']] = $row['service_type_name'];
        }

        return $rows;
    }
}
