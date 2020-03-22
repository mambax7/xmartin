<?php

namespace XoopsModules\Xmartin;

/**
 * Module:martin
 * Licence: GNU
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/xmartin/include/common.php';

/**
 * Class City
 */
class City extends \XoopsObject
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
     * @param string $format
     * @return mixed
     */
    public function city_name($format = 'S')
    {
        return $this->getVar('city_name', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function city_alias($format = 'S')
    {
        return $this->getVar('city_alias', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function city_level($format = 'S')
    {
        return $this->getVar('city_level', $format);
    }
}
