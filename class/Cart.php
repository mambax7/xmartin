<?php

namespace XoopsModules\Xmartin;

/**
 * @
 * @method:
 * @license   http://www.blags.org/
 * @created   :2010年07月03日 21时12分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
class Cart extends \XoopsObject
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
    }
}
