<?php

namespace XoopsModules\Xmartin\Form;

/**
 * @城市表单
 * @license   http://www.blags.org/
 * @created   :2010年05月20日 23时52分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

/**
 * Class FormRoomType
 */
class FormRoomType extends \XoopsThemeForm
{
    /**
     * FormRoomType constructor.
     * @param $roomType
     */
    public function __construct($roomType)
    {
        $this->obj  = $roomType;
        $roomTypeId = isset($roomType['room_type_id']) ? $roomType['room_type_id'] : '';
        parent::__construct(_AM_XMARTIN_HOTEL_ROOM_CATEGORY, 'op', xoops_getenv('SCRIPT_NAME') . '?action=typesave&typeid=' . $roomTypeId);
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
        $roomType     = $this->obj;
        $roomTypeId   = isset($roomType['room_type_id']) ? $roomType['room_type_id'] : '';
        $roomTypeInfo = isset($roomType['room_type_info']) ? $roomType['room_type_info'] : '';
        $this->addElement(new \XoopsFormText(_AM_XMARTIN_ROOM_CATEGORY_NAME, 'room_type_info', 45, 45, $roomTypeInfo), true);
        $this->addElement(new \XoopsFormHidden('typeid', $roomTypeId));
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
        $buttonTray = new \XoopsFormElementTray('', '');
        // No ID for category -- then it's new category, button says 'Create'
        if (empty($this->obj)) {
            $butt_create = new \XoopsFormButton('', '', _SUBMIT, 'submit');
            $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
            $buttonTray->addElement($butt_create);

            $butt_clear = new \XoopsFormButton('', '', _RESET, 'reset');
            $buttonTray->addElement($butt_clear);

            $butt_cancel = new \XoopsFormButton('', '', _CANCEL, 'button');
            $butt_cancel->setExtra('onclick="history.go(-1)"');
            $buttonTray->addElement($butt_cancel);

            $this->addElement($buttonTray);
        } else {
            // button says 'Update'
            $butt_create = new \XoopsFormButton('', '', _EDIT, 'submit');
            $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
            $buttonTray->addElement($butt_create);

            $butt_clear = new \XoopsFormButton('', '', _RESET, 'reset');
            $buttonTray->addElement($butt_clear);

            $butt_cancel = new \XoopsFormButton('', '', _CANCEL, 'button');
            $butt_cancel->setExtra('onclick="history.go(-1)"');
            $buttonTray->addElement($butt_cancel);

            $this->addElement($buttonTray);
        }
    }
}
