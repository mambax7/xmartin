<?php

namespace XoopsModules\Xmartin;

/**
 * Module:martin
 * Licence: GNU
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/xmartin/include/common.php';

/**
 * Class Group
 */
class Group extends \XoopsObject
{
    public function __construct()
    {
        $this->initVar('group_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('group_name', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('group_info', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('check_in_date', XOBJ_DTYPE_INT, null, false);
        $this->initVar('check_out_date', XOBJ_DTYPE_INT, null, false);
        $this->initVar('apply_start_date', XOBJ_DTYPE_INT, null, false);
        $this->initVar('apply_end_date', XOBJ_DTYPE_INT, null, false);
        $this->initVar('group_price', XOBJ_DTYPE_INT, null, false);
        $this->initVar('group_can_use_coupon', XOBJ_DTYPE_INT, null, false);
        $this->initVar('group_sented_coupon', XOBJ_DTYPE_INT, null, false);
        $this->initVar('group_status', XOBJ_DTYPE_INT, null, false);
        $this->initVar('group_add_time', XOBJ_DTYPE_INT, null, false);
    }

    /**
     * @return mixed
     */
    public function group_id()
    {
        return $this->getVar('group_id');
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function group_name($format = 'S')
    {
        return $this->getVar('group_name', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function group_info($format = 'edit')
    {
        return $this->getVar('group_info', $format);
    }

    /**
     * @return mixed
     */
    public function check_in_date()
    {
        return $this->getVar('check_in_date');
    }

    /**
     * @return mixed
     */
    public function check_out_date()
    {
        return $this->getVar('check_out_date');
    }

    /**
     * @return mixed
     */
    public function apply_start_date()
    {
        return $this->getVar('apply_start_date');
    }

    /**
     * @return mixed
     */
    public function apply_end_date()
    {
        return $this->getVar('apply_end_date');
    }

    /**
     * @return mixed
     */
    public function group_price()
    {
        return $this->getVar('group_price');
    }

    /**
     * @return mixed
     */
    public function group_can_use_coupon()
    {
        return $this->getVar('group_can_use_coupon');
    }

    /**
     * @return mixed
     */
    public function group_sented_coupon()
    {
        return $this->getVar('group_sented_coupon');
    }

    /**
     * @return mixed
     */
    public function group_status()
    {
        return $this->getVar('group_status');
    }

    /**
     * @return mixed
     */
    public function group_add_time()
    {
        return $this->getVar('group_add_time');
    }
}
