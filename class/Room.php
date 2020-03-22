<?php

namespace XoopsModules\Xmartin;

/**
 * Module:martin
 * Licence: GNU
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/xmartin/include/common.php';

/**
 * Class Room
 */
class Room extends \XoopsObject
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
     * @param string $format
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
     * @param string $format
     * @return mixed
     */
    public function room_type_info($format = 'S')
    {
        return $this->getVar('room_type_info', $format);
    }

    /**
     * @param string $format
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
     * @param string $format
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
     * @param string $format
     * @return mixed
     */
    public function room_bed_info($format = 'S')
    {
        return $this->getVar('room_bed_info', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function room_sented_coupon($format = 'S')
    {
        return $this->getVar('room_sented_coupon', $format);
    }
}
