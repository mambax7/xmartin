<?php
/**
 * @城市表单
 * @license   http://www.blags.org/
 * @created   :2010年05月20日 23时52分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

/**
 * Class form_hotel_city
 */
class form_hotel_city extends XoopsThemeForm
{
    /**
     * form_hotel_city constructor.
     * @param $HotelCityObj
     */
    public function __construct(&$HotelCityObj)
    {
        $this->Obj =& $HotelCityObj;
        parent::__construct(_AM_MARTIN_HOTEL_CITY, 'op', xoops_getenv('PHP_SELF') . '?action=save');
        $this->setExtra('enctype="multipart/form-data"');

        $this->createElements();
        $this->createButtons();
    }

    /**
     * created elements
     * @license   http://www.blags.org/
     * @created   :2010年05月21日 20时40分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * */
    public function createElements()
    {
        global $xoopsDB;
        $mytree = new XoopsTree($xoopsDB->prefix('martin_hotel_city'), 'city_id', 'city_parentid');
        // Parent Category
        ob_start();
        $mytree->makeMySelBox('city_name', '', $this->Obj->city_parentid(), 1, 'city_parentid');
        //makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange="")
        $this->addElement(new XoopsFormLabel(_AM_MARTIN_PARENT, ob_get_contents()));
        ob_end_clean();
        // City Name
        $this->addElement(new XoopsFormText(_AM_MARTIN_CITY_NAME, 'city_name', 50, 255, $this->Obj->city_name()), true);
        $this->addElement(new XoopsFormText(_AM_MARTIN_CITY_ALIAS, 'city_alias', 50, 255, $this->Obj->city_alias()), true);
        $this->addElement(new XoopsFormHidden('id', $this->Obj->city_id()));
    }

    /**
     * @创建按钮
     * @license   http://www.blags.org/
     * @created   :2010年05月20日 23时52分
     * @copyright 1997-2010 The Martin Group
     * @author    Martin <china.codehome@gmail.com>
     * */
    public function createButtons()
    {
        $button_tray = new XoopsFormElementTray('', '');
        // No ID for category -- then it's new category, button says 'Create'
        if (!$this->Obj->city_id()) {
            $butt_create = new XoopsFormButton('', '', _SUBMIT, 'submit');
            $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
            $button_tray->addElement($butt_create);

            $butt_clear = new XoopsFormButton('', '', _RESET, 'reset');
            $button_tray->addElement($butt_clear);

            $butt_cancel = new XoopsFormButton('', '', _CANCEL, 'button');
            $butt_cancel->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($butt_cancel);

            $this->addElement($button_tray);
        } else {
            // button says 'Update'
            $butt_create = new XoopsFormButton('', '', _SUBMIT, 'submit');
            $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
            $button_tray->addElement($butt_create);

            $butt_clear = new XoopsFormButton('', '', _RESET, 'reset');
            $button_tray->addElement($butt_clear);

            $butt_cancel = new XoopsFormButton('', '', _CANCEL, 'button');
            $butt_cancel->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($butt_cancel);

            $this->addElement($button_tray);
        }
    }
}
