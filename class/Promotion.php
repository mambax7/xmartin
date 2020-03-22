<?php

namespace XoopsModules\Xmartin;

/**
 * Module:martin
 * Licence: GNU
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/xmartin/include/common.php';

/**
 * Class Promotion
 */
class Promotion extends \XoopsObject
{
    public function __construct()
    {
        $this->initVar('promotion_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('hotel_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('hotel_name', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('promotion_start_date', XOBJ_DTYPE_INT, null, false);
        $this->initVar('promotion_end_date', XOBJ_DTYPE_INT, null, false);
        $this->initVar('promotion_description', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('promotion_add_time', XOBJ_DTYPE_INT, null, false);
    }

    /**
     * @return mixed
     */
    public function promotion_id()
    {
        return $this->getVar('promotion_id');
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
    public function hotel_name()
    {
        return $this->getVar('hotel_name');
    }

    /**
     * @return mixed
     */
    public function promotion_start_date()
    {
        return $this->getVar('promotion_start_date');
    }

    /**
     * @return mixed
     */
    public function promotion_end_date()
    {
        return $this->getVar('promotion_end_date');
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function promotion_description($format = 'edit')
    {
        return $this->getVar('promotion_description', $format);
    }

    /**
     * @return mixed
     */
    public function promotion_add_time()
    {
        return $this->getVar('promotion_add_time');
    }
}
