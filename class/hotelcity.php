<?php
/**
 *
 * Module:martin
 * Licence: GNU
 */

defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/martin/include/common.php';

/**
 * Class MartinHotelcity
 */
class MartinHotelcity extends \XoopsObject
{
    public function __construct()
    {
        $this->initVar('city_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('city_parentid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('city_name', XOBJ_DTYPE_TXTBOX, null, true, 45);
        $this->initVar('city_alias', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('city_level', XOBJ_DTYPE_TXTBOX, null, true, 45);
    }

    /**
     * @return mixed
     */
    public function city_id()
    {
        return $this->getVar('city_id');
    }

    /**
     * @return mixed
     */
    public function city_parentid()
    {
        return $this->getVar('city_parentid');
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function city_name($format = 'S')
    {
        return $this->getVar('city_name', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function city_alias($format = 'S')
    {
        return $this->getVar('city_alias', $format);
    }

    /**
     * @param  string $format
     * @return mixed
     */
    public function city_level($format = 'S')
    {
        return $this->getVar('city_level', $format);
    }
}

/**
 * @method: HotelCityHandler
 * @license   http://www.blags.org/
 * @created   :2010年05月21日 20时40分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
class MartinHotelCityHandler extends \XoopsObjectHandler
{
    /**
     * create a new hotel city
     * @param  bool $isNew flag the new objects as "new"?
     * @return object HotelCity
     */
    public function &create($isNew = true)
    {
        $hotelcity = new MartinHotelcity();
        if ($isNew) {
            $hotelcity->setNew();
        }

        return $hotelcity;
    }

    /**
     * retrieve a hotel city
     *
     * @param  int $id hotelcityid of the hotelcity
     * @return mixed reference to the {@link HotelCity} object, FALSE if failed
     */
    public function &get($id)
    {
        if ((int)$id <= 0) {
            return false;
        }

        $criteria = new \CriteriaCompo(new \Criteria('city_id', $id));
        $criteria->setLimit(1);
        $obj_array = $this->getObjects($criteria);
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
     * @param  int    $city_parentid
     * @param  string $sort
     * @param  string $order
     * @param  bool   $id_as_key
     * @return array
     */
    public function &getHotelCitys(
        $limit = 0,
        $start = 0,
        $city_parentid = 0,
        $sort = 'city_id',
        $order = 'ASC',
        $id_as_key = true
    ) {
        $criteria = new \CriteriaCompo();

        $criteria->setSort($sort);
        $criteria->setOrder($order);

        if (-1 != $city_parentid) {
            $criteria->add(new \Criteria('city_parentid', $city_parentid));
        }

        $criteria->setStart($start);
        $criteria->setLimit($limit);

        return $this->getObjects($criteria, $id_as_key);
    }

    /**
     * insert a new hotelcity in the database
     *
     * @param object|\XoopsObject $hotelcity reference to the {@link HotelCity}
     *                                      object
     * @param  bool              $force
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(\XoopsObject $hotelcity, $force = false)
    {
        if ('martinhotelcity' !== strtolower(get_class($hotelcity))) {
            return false;
        }

        if (!$hotelcity->cleanVars()) {
            return false;
        }

        foreach ($hotelcity->cleanVars as $k => $v) {
            ${$k} = $v;
        }

        if ($hotelcity->isNew()) {
            $sql = sprintf('INSERT INTO `%s` (
                                city_id,
                                city_parentid,
                                city_name,
                                city_alias,
                                city_level
                            ) VALUES (
                                NULL,
                                %u,
                                %s,
                                %s,
                                %s
                            )', $this->db->prefix('martin_hotel_city'), $city_parentid, $this->db->quoteString($city_name), $this->db->quoteString($city_alias), $this->db->quoteString($city_level));
        } else {
            $sql = sprintf('UPDATE `%s` SET
                                city_parentid = %u,
                                city_name = %s,
                                city_alias = %s,
                                city_level = %s
                            WHERE city_id = %u', $this->db->prefix('martin_hotel_city'), $city_parentid, $this->db->quoteString($city_name), $this->db->quoteString($city_alias), $this->db->quoteString($city_level), $city_id);
        }
        //echo "<br>" . $sql . "<br>";
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            $hotelcity->setErrors('The query returned an error. ' . $this->db->error());

            return false;
        }
        if ($hotelcity->isNew()) {
            $hotelcity->assignVar('city_id', $this->db->getInsertId());
        }

        $hotelcity->assignVar('city_id', $city_id);

        return true;
    }

    /**
     * @删除一个城市
     * @method:delete(city_id)
     * @license   http://www.blags.org/
     * @created   :2010年05月21日 20时40分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param object|XoopsObject $hotelcity
     * @param  bool              $force
     * @return bool|void
     */
    public function delete(\XoopsObject $hotelcity, $force = false)
    {
        if ('martinhotelcity' !== strtolower(get_class($hotelcity))) {
            return false;
        }

        $subcats =& $this->getCityIds($hotelcity->city_id());

        $sql = 'DELETE FROM ' . $this->db->prefix('martin_hotel_city') . ' WHERE city_id IN ( ' . implode(',', $subcats) . ' )';

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
     * @ 得到所有子类
     * @license   http://www.blags.org/
     * @created   :2010年05月21日 20时40分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param $city_id
     * @return array
     */
    public function getCityIds($city_id)
    {
        if (!is_array($city_id)) {
            $city_id = [(int)$city_id];
        }
        $cities = $city_id;
        //var_dump($cities);exit;
        $sql = 'SELECT city_id FROM ' . $this->db->prefix('martin_hotel_city') . ' WHERE city_parentid IN (' . implode(',', $cities) . ')';
        //echo $sql;exit;

        $result = $this->db->query($sql);
        while (false !== ($row = $this->db->fetchArray($result))) {
            $cities[] = (int)$row['city_id'];
        }
        $cities = array_unique($cities);
        //var_dump($cities);exit;
        //var_dump($city_id);exit;
        $isOver = array_diff($cities, $city_id);
        //var_dump($isOver);exit;
        if (empty($isOver)) {
            return $cities;
        }

        return $this->getCityIds($cities);
    }

    /**
     * delete hotel cities matching a set of conditions
     *
     * @param  object $criteria {@link CriteriaElement}
     * @return bool   FALSE if deletion failed
     */
    public function deleteAll($criteria = null)
    {
        $sql = 'DELETE FROM ' . $this->db->prefix('martin_hotel_city');
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
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('martin_hotel_city');
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
        $sql   = 'SELECT * FROM ' . $this->db->prefix('martin_hotel_city');
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
            $hotelcity = new MartinHotelcity();
            $hotelcity->assignVars($myrow);
            $theObjects[$myrow['city_id']] =& $hotelcity;
            //var_dump($hotelcity);
            unset($hotelcity);
        }
        //var_dump($theObjects);

        foreach ($theObjects as $theObject) {
            if (!$id_as_key) {
                $ret[] =& $theObject;
            } else {
                $ret[$theObject->city_id()] =& $theObject;
            }
            unset($theObject);
        }

        return $ret;
    }

    /**
     * @get       city tree
     * @license   http://www.blags.org/
     * @created   :2010年05月29日 11时31分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * @param         $name
     * @param         $city_id
     * @param  string $prefix
     * @return string
     */
    public function &getTree($name, $city_id, $prefix = '--')
    {
        $mytree = new \XoopsTree($this->db->prefix('martin_hotel_city'), 'city_id', 'city_parentid');
        // Parent Category
        ob_start();
        $mytree->makeMySelBox('city_name', '', $city_id, 1, $name);
        //makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
        $str = ob_get_contents();
        ob_end_clean();

        return $str;
    }
}
