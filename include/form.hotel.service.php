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
 * Class form_hotel_service
 */
class form_hotel_service extends XoopsThemeForm
{
    /**
     * form_hotel_service constructor.
     * @param $HotelServiceObj
     * @param $TypeList
     */
    public function __construct(&$HotelServiceObj, &$TypeList)
    {
        $this->Obj      =& $HotelServiceObj;
        $this->TypeList =& $TypeList;
        parent::__construct(_AM_MARTIN_HOTEL_SERVICE, 'op', xoops_getenv('PHP_SELF') . '?action=save');
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
        $TypeElement = new XoopsFormSelect(_AM_MARTIN_SERVICE_TYPE, 'service_type_id', $this->Obj->service_type_id(), 1);
        $TypeElement->addOptionArray($this->TypeList);
        $this->addElement($TypeElement, true);
        $this->addElement(new XoopsFormText(_AM_MARTIN_SERVICE_UNITS . '<br>' . _AM_MARTIN_SERVICE_UNITS_DESC, 'service_unit', 45, 45, $this->Obj->service_unit()), true);
        $this->addElement(new XoopsFormText(_AM_MARTIN_SERVICE_NAME, 'service_name', 50, 255, $this->Obj->service_name()), true);
        $this->addElement(new XoopsFormTextArea(_AM_MARTIN_SERVICE_DESCRIPTION, 'service_instruction', $this->Obj->service_instruction()), true);
        $this->addElement(new XoopsFormHidden('id', $this->Obj->service_id()));
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
        if (!$this->Obj->service_id()) {
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
            $butt_create = new XoopsFormButton('', '', _EDIT, 'submit');
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
