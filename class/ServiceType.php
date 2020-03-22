<?php

namespace XoopsModules\Xmartin;

/**
 * Module:martin
 * Licence: GNU
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/xmartin/include/common.php';

/**
 * Class ServiceType
 */
class ServiceType extends \XoopsObject
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
     * @param string $format
     * @return mixed
     */
    public function service_type_name($format = 'S')
    {
        return $this->getVar('service_type_name', $format);
    }
}
