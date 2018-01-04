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
 * Class form_group
 */
class form_group extends XoopsThemeForm
{
    /**
     * form_group constructor.
     * @param $GroupObj
     * @param $RoomList
     * @param $HotelList
     */
    public function __construct(&$GroupObj, &$RoomList, &$HotelList)
    {
        $this->Obj       =& $GroupObj;
        $this->RoomList  =& $RoomList;
        $this->HotelList =& $HotelList;
        parent::__construct(_AM_MARTIN_HOTEL_GROUP_BUY, 'op', xoops_getenv('PHP_SELF') . '?action=save');
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
        require_once MARTIN_ROOT_PATH . '/include/formdatetime.php';

        $RoomStr = '';
        foreach ($this->RoomList as $room) {
            $RoomStr .= "<br><input type=checkbox name=room_id[] value={$room['room_id']} id=room_{$room['room_id']} checked=\"checked\" click=\"RoomRemove(this)\">&nbsp;<label for=room_{$room['room_id']}>{$room['room_name']}</label>&nbsp;&nbsp;"
                        . _AM_MARTIN_NUMBER_OF_ROOMS
                        . ":<input type=text name=\"room_count_{$room['room_id']}\" value={$room['room_count']}>";
        }
        $js          = '<script type=\'text/javascript\'>
            jQuery.noConflict();
            jQuery(document).ready(function($){
                $("#hotel_id").click(function(){
                    var hotel_id =  Number($(this).val());
                    $.post("martin.ajax.php?action=getroomlist",{hotel_id:hotel_id},function(data){
                        $("#room").html(data);
                    });
                });
            });
            function roomSelect(event)
            {
                var room_id = Number(jQuery("#ajaxroom").val());
                var room_name = jQuery.trim(jQuery("#ajaxroom option:selected").text());
                var roomExist = document.getElementById("room_"+room_id);
                if(roomExist) return false;
                if(room_id > 0)
                {
                    var Str = "<br><input type=checkbox name=room_id[] value="+room_id+" id=room_"+room_id+" checked=\"checked\" click=\"RoomRemove(this)\">&nbsp;"+"<label for=room_"+room_id+">" + room_name + "</label>";
                    var inputStr = "&nbsp;&nbsp;"._AM_MARTIN_NUMBER_OF_ROOMS.":<input type=text name=\"room_count_"+room_id+"\" value=1>";
                    jQuery("#rooms").append(Str + inputStr);
                }
            }
            </script>';
        $Room        = new XoopsFormElementTray($js . _AM_MARTIN_SELECT_ROOMS . '<br>' . _AM_MARTIN_FILTER_BY_HOTEL);
        $RoomElement = new XoopsFormSelect('', 'hotel_id', $this->HotelList, 5, false);
        $RoomElement->addOptionArray($this->HotelList);
        //$RoomElement->addOption('class','hotel');
        $Room->addElement($RoomElement, false);
        $Room->addElement(new XoopsFormElementTray('<br><br><div id="room"></div><div id="rooms">' . $RoomStr . '</div>'), false);

        $this->addElement($Room, false);

        $this->addElement(new XoopsFormTextDateSelect(_AM_MARTIN_CHECK_IN, 'check_in_date', $size = 15, $this->Obj->check_in_date(), false), true);

        $this->addElement(new XoopsFormTextDateSelect(_AM_MARTIN_LAUNCH_TIME, 'check_out_date', $size = 15, $this->Obj->check_out_date(), false), true);
        //$this->addElement( new XoopsFormTextDateSelect(_AM_MARTIN_HOTEL_APPLY_START_TIME, 'apply_start_date', $size = 15, $this->Obj->apply_start_date(),false ) ,true);
        $this->addElement(new MartinFormDateTime(_AM_MARTIN_HOTEL_APPLY_START_TIME, 'apply_start_date', $size = 15, $this->Obj->apply_start_date()), true);
        //$this->addElement( new XoopsFormTextDateSelect(_AM_MARTIN_END_BUY_TIME, 'apply_end_date', $size = 15, $this->Obj->apply_end_date() ) ,true);
        $this->addElement(new MartinFormDateTime(_AM_MARTIN_END_BUY_TIME, 'apply_end_date', $size = 15, $this->Obj->apply_end_date()), true);

        $this->addElement(new XoopsFormText(_AM_MARTIN_PRICE, 'group_price', 11, 11, $this->Obj->group_price()), true);
        $this->addElement(new XoopsFormText(_AM_MARTIN_GIFT_VOUCHER . '?', 'group_sented_coupon', 11, 11, (int)$this->Obj->group_sented_coupon()), true);
        $this->addElement(new XoopsFormRadioYN(_AM_MARTIN_CAN_YOU_USE_CASH_VOLUME, 'group_can_use_coupon', $this->Obj->group_can_use_coupon(), _YES, _NO), true);
        $this->addElement(new XoopsFormRadioYN(_AM_MARTIN_STATUS, 'group_status', $this->Obj->group_status(), _AM_MARTIN_PUBLISHED, _AM_MARTIN_DRAFT), true);

        $this->addElement(new XoopsFormText(_AM_MARTIN_TITLE, 'group_name', 50, 255, $this->Obj->group_name()), true);
        $editor     = 'tinymce';
        $group_info = $this->Obj->group_info();
        //var_dump($group_info);
        $editor_configs           = [];
        $editor_configs['name']   = 'group_info';
        $editor_configs['value']  = $group_info;
        $editor_configs['rows']   = empty($xoopsModuleConfig['editor_rows']) ? 35 : $xoopsModuleConfig['editor_rows'];
        $editor_configs['cols']   = empty($xoopsModuleConfig['editor_cols']) ? 60 : $xoopsModuleConfig['editor_cols'];
        $editor_configs['width']  = empty($xoopsModuleConfig['editor_width']) ? '100%' : $xoopsModuleConfig['editor_width'];
        $editor_configs['height'] = empty($xoopsModuleConfig['editor_height']) ? '400px' : $xoopsModuleConfig['editor_height'];

        $this->addElement(new XoopsFormEditor(_AM_MARTIN_CUSTOMERS_DETAILS, $editor, $editor_configs, false, $onfailure = null), false);
        $this->addElement(new XoopsFormHidden('id', $this->Obj->group_id()));
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
        if (!$this->Obj->group_id()) {
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
