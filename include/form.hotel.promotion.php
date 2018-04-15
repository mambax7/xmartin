<?php
/**
 * @城市表单
 * @license   http://www.blags.org/
 * @created   :2010年05月20日 23时52分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */

use XoopsModules\Xmartin;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

/**
 * Class form_hotel_promotion
 */
class form_hotel_promotion extends \XoopsThemeForm
{
    /**
     * form_hotel_promotion constructor.
     * @param $HotelPromotionObj
     * @param $HotelList
     */
    public function __construct(&$HotelPromotionObj, &$HotelList)
    {
        $this->Obj       =& $HotelPromotionObj;
        $this->HotelList =& $HotelList;
        parent::__construct(_AM_MARTIN_HOTEL_CITY_LIST, 'op', xoops_getenv('PHP_SELF') . '?action=save');
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
        //require_once MARTIN_ROOT_PATH . '/include/formdatetime.php';
        require_once XOOPS_ROOT_PATH . '/modules/martin/class/xoopsformloader.php';

        /** @var Xmartin\Helper $helper */
        $helper = Xmartin\Helper::getInstance();

        $js          = '<script type=\'text/javascript\'>
            jQuery.noConflict();
            </script>';
        $RoomElement = new \XoopsFormSelect($js . _AM_MARTIN_PROMO_HOTELS, 'hotel_id', $this->HotelList, 0, false);
        $RoomElement->addOptionArray($this->HotelList);
        $this->addElement($RoomElement, false);

        $this->addElement(new \XoopsFormTextDateSelect(_AM_MARTIN_PROMO_START, 'promotion_start_date', $size = 15, $this->Obj->promotion_start_date(), false), true);

        $this->addElement(new \XoopsFormTextDateSelect(_AM_MARTIN_PROMO_END, 'promotion_end_date', $size = 15, $this->Obj->promotion_end_date(), false), true);

        $editor                   = 'tinymce';
        $editor_configs           = [];
        $editor_configs['name']   = 'promotion_description';
        $editor_configs['value']  = $this->Obj->promotion_description();
        $editor_configs['rows']   = empty($helper->getConfig('editor_rows')) ? 35 : $helper->getConfig('editor_rows');
        $editor_configs['cols']   = empty($helper->getConfig('editor_cols')) ? 60 : $helper->getConfig('editor_cols');
        $editor_configs['width']  = empty($helper->getConfig('editor_width')) ? '100%' : $helper->getConfig('editor_width');
        $editor_configs['height'] = empty($helper->getConfig('editor_height')) ? '400px' : $helper->getConfig('editor_height');
        $this->addElement(new \XoopsFormEditor(_AM_MARTIN_PROMOTION_DETAILS, $editor, $editor_configs, false, $onfailure = null), false);

        $this->addElement(new \XoopsFormHidden('id', $this->Obj->promotion_id()));
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
        if (!$this->Obj->promotion_id()) {
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
