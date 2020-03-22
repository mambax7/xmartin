<?php

namespace XoopsModules\Xmartin;

/**
 * Module:martin
 * Licence: GNU
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/xmartin/include/common.php';

/**
 * Class Order
 */
class Order extends \XoopsObject
{
    public function __construct()
    {
        $this->initVar('order_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('order_type', XOBJ_DTYPE_INT, null, false);
        $this->initVar('order_mode', XOBJ_DTYPE_INT, null, false);
        $this->initVar('order_uid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('order_pay_method', XOBJ_DTYPE_INT, null, false);
        $this->initVar('order_pay', XOBJ_DTYPE_TXTBOX, null, true, 25);
        $this->initVar('order_status', XOBJ_DTYPE_INT, null, false);
        $this->initVar('order_total_price', XOBJ_DTYPE_INT, null, false);
        $this->initVar('order_pay_money', XOBJ_DTYPE_INT, null, false);
        $this->initVar('order_coupon', XOBJ_DTYPE_INT, null, false);
        $this->initVar('order_sented_coupon', XOBJ_DTYPE_INT, null, false);
        $this->initVar('order_real_name', XOBJ_DTYPE_TXTBOX, null, true, 45);
        $this->initVar('order_document_type', XOBJ_DTYPE_INT, null, false);
        $this->initVar('order_document', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('order_telephone', XOBJ_DTYPE_TXTBOX, null, true, 45);
        $this->initVar('order_phone', XOBJ_DTYPE_TXTBOX, null, true, 45);
        $this->initVar('order_extra_persons', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('order_note', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('order_status_time', XOBJ_DTYPE_INT, null, false);
        $this->initVar('order_submit_time', XOBJ_DTYPE_INT, null, false);
        //room

        //users
        $this->initVar('uname', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('email', XOBJ_DTYPE_TXTBOX, null, true, 255);
    }

    /**
     * @return mixed
     */
    public function order_id()
    {
        return $this->getVar('order_id');
    }

    /**
     * @return mixed
     */
    public function order_type()
    {
        return $this->getVar('order_type');
    }

    /**
     * @return mixed
     */
    public function order_mode()
    {
        return $this->getVar('order_mode');
    }

    /**
     * @return mixed
     */
    public function order_uid()
    {
        return $this->getVar('order_uid');
    }

    /**
     * @return mixed
     */
    public function order_pay_method()
    {
        return $this->getVar('order_pay_method');
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function order_pay($format = 'S')
    {
        return $this->getVar('order_pay', $format);
    }

    /**
     * @return mixed
     */
    public function order_status()
    {
        return $this->getVar('order_status');
    }

    /**
     * @return mixed
     */
    public function order_total_price()
    {
        return $this->getVar('order_total_price');
    }

    /**
     * @return mixed
     */
    public function order_pay_money()
    {
        return $this->getVar('order_pay_money');
    }

    /**
     * @return mixed
     */
    public function order_coupon()
    {
        return $this->getVar('order_coupon');
    }

    /**
     * @return mixed
     */
    public function order_sented_coupon()
    {
        return $this->getVar('order_sented_coupon');
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function order_real_name($format = 'S')
    {
        return $this->getVar('order_real_name', $format);
    }

    /**
     * @return mixed
     */
    public function order_document_type()
    {
        return $this->getVar('order_document_type');
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function order_document($format = 'S')
    {
        return $this->getVar('order_document', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function order_telephone($format = 'S')
    {
        return $this->getVar('order_telephone', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function order_phone($format = 'S')
    {
        return $this->getVar('order_phone', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function order_extra_persons($format = 'S')
    {
        return unserialize($this->getVar('order_extra_persons', $format));
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function order_note($format = 'S')
    {
        return $this->getVar('order_note', $format);
    }

    /**
     * @return mixed
     */
    public function order_status_time()
    {
        return $this->getVar('order_status_time');
    }

    /**
     * @return mixed
     */
    public function order_submit_time()
    {
        return $this->getVar('order_submit_time');
    }

    //rooms

    //users

    /**
     * @param string $format
     * @return mixed
     */
    public function uname($format = 'S')
    {
        return $this->getVar('uname', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function email($format = 'S')
    {
        return $this->getVar('email', $format);
    }
}
