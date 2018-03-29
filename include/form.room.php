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
 * Class form_room
 */
class form_room extends XoopsThemeForm
{
    /**
     * form_room constructor.
     * @param $RoomObj
     * @param $hotelList
     * @param $TypeList
     */
    public function __construct(&$RoomObj, &$hotelList, &$TypeList)
    {
        $this->Obj             =& $RoomObj;
        $this->hotelList       =& $hotelList;
        $this->TypeList        =& $TypeList;
        $this->RoomBedTypeList = getModuleArray('room_bed_type', 'order_type', true);
        parent::__construct(_AM_MARTIN_HOTEL_ROOMS, 'op', xoops_getenv('PHP_SELF') . '?action=save');
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
        $TypeElement = new \XoopsFormSelect(_AM_MARTIN_ROOM_CATEGORY, 'room_type_id', $this->Obj->room_type_id(), 1);
        $TypeElement->addOptionArray($this->TypeList);
        $this->addElement($TypeElement, true);
        $HotelElement = new \XoopsFormSelect(_AM_MARTIN_HOTEL_NAME, 'hotel_id', $this->Obj->hotel_id(), 1);
        $HotelElement->addOptionArray($this->hotelList);
        $this->addElement($HotelElement, true);

        $BedTypeElement = new \XoopsFormSelect(_AM_MARTIN_ROOM_BED_TYPE, 'room_bed_type', $this->Obj->room_bed_type(), 1);
        $BedTypeElement->addOptionArray($this->RoomBedTypeList);
        $this->addElement($BedTypeElement, true);

        //$this->addElement( new \XoopsFormText('客房初始价格', 'room_initial_price', 11, 11, $this->Obj->room_initial_price()), true);
        $this->addElement(new \XoopsFormText(_AM_MARTIN_ROOM_COUNT, 'room_count', 11, 11, $this->Obj->room_count()), true);
        $this->addElement(new \XoopsFormText(_AM_MARTIN_ROOMS_AREA, 'room_area', 11, 11, $this->Obj->room_area()), true);
        $this->addElement(new \XoopsFormText(_AM_MARTIN_ROOM_NAME, 'room_name', 45, 45, $this->Obj->room_name()), true);
        $this->addElement(new \XoopsFormText(_AM_MARTIN_ROOM_FLOOR, 'room_floor', 45, 45, $this->Obj->room_floor()), true);

        /*$isHidden = $this->Obj->room_is_add_bed() ? '' :'$("#room_add_money").parent("td").parent("tr").hide();$("#room_bed_info").parent("td").parent("tr").hide();';
        $js = '<script type=\'text/javascript\'>
        jQuery.noConflict();
        jQuery(document).ready(function($){
            '.$isHidden.'
            $("#room_add_money").parent("td").parent("tr").prev("tr").find("input[type=\"radio\"]").click(function(){
                if(Number($(this).val()) > 0)
                {
                    $("#room_add_money").parent("td").parent("tr").show();
                    $("#room_bed_info").parent("td").parent("tr").show();
                }else{
                    $("#room_add_money").parent("td").parent("tr").hide();
                    $("#room_bed_info").parent("td").parent("tr").hide();
                }
            });
        });
        </script>';*/
        //$this->addElement( new \XoopsFormRadioYN("是否加床", "room_is_add_bed", $this->Obj->room_is_add_bed() , _YES, " " . _NO . $js ) , true);
        //$this->addElement( new \XoopsFormText('加价', 'room_add_money', 11, 11, $this->Obj->room_add_money()), false);
        $this->addElement(new \XoopsFormTextArea(_AM_MARTIN_ROOM_DESCRIPTION, 'room_bed_info', $this->Obj->room_bed_info()), false);
        $this->addElement(new \XoopsFormRadioYN(_AM_MARTIN_ROOM_STATUS, 'room_status', $this->Obj->room_status(), _AM_MARTIN_PUBLISHED, _AM_MARTIN_DRAFT), true);
        //$this->addElement( new \XoopsFormText(_AM_MARTIN_GIFT_VOUCHER, 'room_sented_coupon', 11, 11, (int)($this->Obj->room_sented_coupon())), false);

        $this->addElement(new \XoopsFormHidden('id', $this->Obj->room_id()));
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
        if (!$this->Obj->room_id()) {
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
