<?php

namespace XoopsModules\Xmartin;

/**
 * Module:martin
 * Licence: GNU
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once XOOPS_ROOT_PATH . '/modules/xmartin/include/common.php';

/**
 * Class Hotel
 */
class Hotel extends \XoopsObject
{
    public function __construct()
    {
        /** @var \XoopsModules\Xmartin\Helper $this->helper */
        $this->helper = \XoopsModules\Xmartin\Helper::getInstance();
        $this->initVar('hotel_id', XOBJ_DTYPE_INT, null, false);
        //$this->initVar("hotel_city_id", XOBJ_DTYPE_INT, null, false);
        $this->initVar('hotel_city', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('hotel_city_id', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('hotel_environment', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('hotel_rank', XOBJ_DTYPE_INT, null, false);
        $this->initVar('hotel_name', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('hotel_enname', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('hotel_alias', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('hotel_keywords', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('hotel_tags', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('hotel_description', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('hotel_star', XOBJ_DTYPE_INT, null, false);
        $this->initVar('hotel_address', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('hotel_telephone', XOBJ_DTYPE_TXTBOX, null, true, 45);
        $this->initVar('hotel_fax', XOBJ_DTYPE_TXTBOX, null, true, 45);
        $this->initVar('hotel_room_count', XOBJ_DTYPE_INT, null, false);
        $this->initVar('hotel_icon', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('hotel_image', XOBJ_DTYPE_ARRAY, '', true, 1000);
        $this->initVar('hotel_google', XOBJ_DTYPE_ARRAY, '', true, 255);
        $this->initVar('hotel_characteristic', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('hotel_reminded', XOBJ_DTYPE_TXTBOX, null, true, 1000);
        $this->initVar('hotel_facility', XOBJ_DTYPE_TXTBOX, null, true, 1000);
        $this->initVar('hotel_info', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('hotel_status', XOBJ_DTYPE_INT, null, false);
        $this->initVar('hotel_open_time', XOBJ_DTYPE_INT, null, false);
        $this->initVar('hotel_add_time', XOBJ_DTYPE_INT, null, false);
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
    public function hotel_city()
    {
        return $this->getVar('hotel_city');
    }

    /*function hotel_city_id()
    {
        return $this->getVar("hotel_city_id");
    }*/

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_city_id($format = 'S')
    {
        return $this->getVar('hotel_city_id', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_environment($format = 'S')
    {
        return $this->getVar('hotel_environment', $format);
    }

    /**
     * @return mixed
     */
    public function hotel_rank()
    {
        return $this->getVar('hotel_rank');
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_city_name($format = 'S')
    {
        return $this->getVar('hotel_city_name', $format);
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
     * @param string $format
     * @return mixed
     */
    public function hotel_enname($format = 'S')
    {
        return $this->getVar('hotel_enname', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_alias($format = 'S')
    {
        return $this->getVar('hotel_alias', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_keywords($format = 'S')
    {
        return $this->getVar('hotel_keywords', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_tags($format = 'S')
    {
        return $this->getVar('hotel_tags', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_description($format = 'S')
    {
        return $this->getVar('hotel_description', $format);
    }

    /**
     * @return mixed
     */
    public function hotel_star()
    {
        return $this->getVar('hotel_star');
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_address($format = 'S')
    {
        return $this->getVar('hotel_address', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_telephone($format = 'S')
    {
        return $this->getVar('hotel_telephone', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_fax($format = 'S')
    {
        return $this->getVar('hotel_fax', $format);
    }

    /**
     * @return mixed
     */
    public function hotel_room_count()
    {
        return $this->getVar('hotel_room_count');
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_icon($format = 'S')
    {
        return $this->getVar('hotel_icon', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_image($format = 'S')
    {
        return unserialize($this->getVar('hotel_image', $format));
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_google($format = 'S')
    {
        return unserialize($this->getVar('hotel_google', $format));
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_characteristic($format = 'S')
    {
        return $this->getVar('hotel_characteristic', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_reminded($format = 'S')
    {
        return $this->getVar('hotel_reminded', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_facility($format = 'S')
    {
        return $this->getVar('hotel_facility', $format);
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function hotel_info($format = 'edit')
    {
        return $this->getVar('hotel_info', $format);
    }

    /**
     * @return mixed
     */
    public function hotel_status()
    {
        return $this->getVar('hotel_status');
    }

    /**
     * @return mixed
     */
    public function hotel_open_time()
    {
        return $this->getVar('hotel_open_time');
    }

    /**
     * @return mixed
     */
    public function hotel_add_time()
    {
        return $this->getVar('hotel_add_time');
    }
}
