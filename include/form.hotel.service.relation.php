<?php
/**
 * @城市表单
 * @license   http://www.blags.org/
 * @created   :2010年05月20日 23时52分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

/**
 * Class form_hotel_service_relation
 */
class form_hotel_service_relation extends \XoopsThemeForm
{
    /**
     * form_hotel_service_relation constructor.
     * @param $Relation
     * @param $serviceList
     * @param $hotelList
     */
    public function __construct(&$Relation, &$serviceList, &$hotelList)
    {
        $this->Obj         =& $Relation;
        $this->serviceList =& $serviceList;
        $this->hotelList   =& $hotelList;
        parent::__construct(_AM_MARTIN_HOTEL_ASSOCIATION, 'op', xoops_getenv('PHP_SELF') . "?action=hotelsave&hotel_id={$Relation['hotel_id']}&service_id={$Relation['service_id']}");
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
        $Relation =& $this->Obj;

        $HotelElement = new \XoopsFormSelect(_AM_MARTIN_HOTEL_NAME, 'hotel_id', $Relation['hotel_id'], 1);
        $HotelElement->addOptionArray($this->hotelList);
        $this->addElement($HotelElement, true);

        $ServiceElement = new \XoopsFormSelect(_AM_MARTIN_SERVICE_NAME, 'service_id', $Relation['service_id'], 1);
        $ServiceElement->addOptionArray($this->serviceList);
        $this->addElement($ServiceElement, true);

        $this->addElement(new \XoopsFormText(_AM_MARTIN_SERVICE_PRICES, 'service_extra_price', 11, 11, $Relation['service_extra_price']), true);
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
        $button_tray = new \XoopsFormElementTray('', '');
        // No ID for category -- then it's new category, button says 'Create'
        if (!$this->Obj) {
            $butt_create = new \XoopsFormButton('', '', _SUBMIT, 'submit');
            $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
            $button_tray->addElement($butt_create);

            $butt_clear = new \XoopsFormButton('', '', _RESET, 'reset');
            $button_tray->addElement($butt_clear);

            $butt_cancel = new \XoopsFormButton('', '', _CANCEL, 'button');
            $butt_cancel->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($butt_cancel);

            $this->addElement($button_tray);
        } else {
            // button says 'Update'
            $butt_create = new \XoopsFormButton('', '', _EDIT, 'submit');
            $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
            $button_tray->addElement($butt_create);

            $butt_clear = new \XoopsFormButton('', '', _RESET, 'reset');
            $button_tray->addElement($butt_clear);

            $butt_cancel = new \XoopsFormButton('', '', _CANCEL, 'button');
            $butt_cancel->setExtra('onclick="history.go(-1)"');
            $button_tray->addElement($butt_cancel);

            $this->addElement($button_tray);
        }
    }
}
