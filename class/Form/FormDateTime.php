<?php

namespace XoopsModules\Xmartin\Form;

/**
 * @method:日历类
 * @license   http://www.blags.org/
 * @created   :2010年05月25日 22时33分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

/**
 * @method: 日期
 * @license   http://www.blags.org/
 * @created   :2010年05月25日 22时33分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
class FormDateTime extends \XoopsFormElementTray
{
    /**
     * FormDateTime constructor.
     * @param      $caption
     * @param      $name
     * @param int  $size
     * @param int  $value
     * @param bool $is_time
     */
    public function __construct($caption, $name, $size = 15, $value = 0, $is_time = true)
    {
        parent::__construct($caption, '&nbsp;');
        $value    = (int)$value;
        $value    = ($value > 0) ? $value : time();
        $datetime = getdate($value);
        $this->addElement(new \XoopsFormTextDateSelect('', $name . '[date]', $size, $value));
        $timearray = [];
        for ($i = 0; $i < 24; $i++) {
            for ($j = 0; $j < 60; $j = $j + 10) {
                $key             = ($i * 3600) + ($j * 60);
                $timearray[$key] = (0 != $j) ? $i . ':' . $j : $i . ':0' . $j;
            }
        }
        ksort($timearray);
        $timeselect = new \XoopsFormSelect('', $name . '[time]', $datetime['hours'] * 3600 + 600 * floor($datetime['minutes'] / 10));
        $timeselect->addOptionArray($timearray);
        $this->addElement($timeselect);
    }
}
