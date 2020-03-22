<?php

namespace XoopsModules\Xmartin;

/**
 * Module:martin
 * Licence: GNU
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/xmartin/include/common.php';

/**
 * Class HotelService
 */
class HotelService extends \XoopsObject
{
    public function __construct()
    {
        $this->initVar('service_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('service_type_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('service_type_name', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('service_unit', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('service_name', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('service_instruction', XOBJ_DTYPE_TXTAREA, null, false);
    }

    /**
     * @return mixed
     */
    public function service_id()
    {
        return $this->getVar('service_id');
    }

    /**
     * @return mixed
     */
    public function service_type_id()
    {
        return $this->getVar('service_type_id');
    }

    /**
     * @return mixed
     */
    public function service_type_name()
    {
        return $this->getVar('service_type_name');
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function service_unit($format = 'S')
    {
        return $this->getVar('service_unit', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function service_name($format = 'S')
    {
        return $this->getVar('service_name', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function service_instruction($format = 'S')
    {
        return $this->getVar('service_instruction', $format);
    }
}
