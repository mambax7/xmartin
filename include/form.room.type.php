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
 * Class form_room_type
 */
class form_room_type extends XoopsThemeForm
{
    /**
     * form_room_type constructor.
     * @param $RoomType
     */
    public function __construct(&$RoomType)
    {
        $this->Obj =& $RoomType;
        parent::__construct(_AM_MARTIN_HOTEL_ROOM_CATEGORY, 'op', xoops_getenv('PHP_SELF') . '?action=typesave&typeid=' . $RoomType['room_type_id']);
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
        $RoomType =& $this->Obj;
        $this->addElement(new XoopsFormText(_AM_MARTIN_ROOM_CATEGORY_NAME, 'room_type_info', 45, 45, $RoomType['room_type_info']), true);
        $this->addElement(new XoopsFormHidden('typeid', $RoomType['room_type_id']));
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
        if (empty($this->Obj)) {
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
