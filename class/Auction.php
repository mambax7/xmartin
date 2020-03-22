<?php

namespace XoopsModules\Xmartin;

/**
 * Module:martin
 * Licence: GNU
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/xmartin/include/common.php';

/**
 * Class Auction
 */
class Auction extends \XoopsObject
{
    public function __construct()
    {
        $this->initVar('auction_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('auction_name', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('auction_info', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('check_in_date', XOBJ_DTYPE_INT, null, false);
        $this->initVar('check_out_date', XOBJ_DTYPE_INT, null, false);
        $this->initVar('apply_start_date', XOBJ_DTYPE_INT, null, false);
        $this->initVar('apply_end_date', XOBJ_DTYPE_INT, null, false);
        $this->initVar('auction_price', XOBJ_DTYPE_INT, null, false);
        $this->initVar('auction_low_price', XOBJ_DTYPE_INT, null, false);
        $this->initVar('auction_add_price', XOBJ_DTYPE_INT, null, false);
        $this->initVar('auction_can_use_coupon', XOBJ_DTYPE_INT, null, false);
        $this->initVar('auction_sented_coupon', XOBJ_DTYPE_INT, null, false);
        $this->initVar('auction_status', XOBJ_DTYPE_INT, null, false);
        $this->initVar('auction_add_time', XOBJ_DTYPE_INT, null, false);
    }

    /**
     * @return mixed
     */
    public function auction_id()
    {
        return $this->getVar('auction_id');
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function auction_name($format = 'S')
    {
        return $this->getVar('auction_name', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function auction_info($format = 'edit')
    {
        return $this->getVar('auction_info', $format);
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
    public function auction_price()
    {
        return $this->getVar('auction_price');
    }

    /**
     * @return mixed
     */
    public function auction_low_price()
    {
        return $this->getVar('auction_low_price');
    }

    /**
     * @return mixed
     */
    public function auction_add_price()
    {
        return $this->getVar('auction_add_price');
    }

    /**
     * @return mixed
     */
    public function auction_can_use_coupon()
    {
        return $this->getVar('auction_can_use_coupon');
    }

    /**
     * @return mixed
     */
    public function auction_sented_coupon()
    {
        return $this->getVar('auction_sented_coupon');
    }

    /**
     * @return mixed
     */
    public function auction_status()
    {
        return $this->getVar('auction_status');
    }

    /**
     * @return mixed
     */
    public function auction_add_time()
    {
        return $this->getVar('auction_add_time');
    }
}
