<?php

namespace XoopsModules\Xmartin\Form;

/**
 * @城市表单
 * @license   http://www.blags.org/
 * @created   :2010年05月20日 23时52分
 * @copyright 1997-2010 The Martin auction
 * @author    Martin <china.codehome@gmail.com>
 * */

use XoopsModules\Xmartin;

//defined('XOOPS_ROOT_PATH') || exit('Restricted access');

//require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

/**
 * Class FormAuction
 */
class FormAuction extends \XoopsThemeForm
{
    public $obj;
    public $roomList;
    public $hotelList;

    /**
     * FormAuction constructor.
     * @param mixed $auctionObj
     * @param array $roomList
     * @param array $hotelList
     */
    public function __construct($auctionObj, $roomList, $hotelList)
    {
        $this->obj       = $auctionObj;
        $this->roomList  = $roomList;
        $this->hotelList = $hotelList;
        parent::__construct(_AM_XMARTIN_HOTEL_BIDDING, 'op', xoops_getenv('SCRIPT_NAME') . '?action=save');
        $this->setExtra('enctype="multipart/form-data"');

        $this->createElements();
        $this->createButtons();
    }

    /**
     * created elements
     * @license   http://www.blags.org/
     * @created   :2010年05月21日 20时40分
     * @copyright 1997-2010 The Martin auction
     * @author    Martin <china.codehome@gmail.com>
     * */
    public function createElements()
    {
        //require_once XMARTIN_ROOT_PATH . '/include/formdatetime.php';
        //require_once XOOPS_ROOT_PATH . '/modules/xmartin/class/xoopsformloader.php';
        //require_once XMARTIN_ROOT_PATH . '/include/formdatetime.php';

        /** @var \XoopsModules\Xmartin\Helper $helper */
        $helper = \XoopsModules\Xmartin\Helper::getInstance();

        $roomStr = '';
        foreach ($this->roomList as $room) {
            $roomStr .= "<br><input type=checkbox name=room_id[] value={$room['room_id']} id=room_{$room['room_id']} checked=\"checked\" click=\"RoomRemove(this)\">&nbsp;<label for=room_{$room['room_id']}>{$room['room_name']}</label>&nbsp;&nbsp;"
                        . _AM_XMARTIN_NUMBER_OF_ROOMS
                        . ":<input type=text name=\"room_count_{$room['room_id']}\" value={$room['room_count']}>";
        }
        $js          = '<script type=\'text/javascript\'>
            jQuery.noConflict();
            jQuery(document).ready(function($){
                $("#hotel_id").click(function(){
                    var hotel_id =  Number($(this).val());
                    $.post("ajax.php?action=getroomlist",{hotel_id:hotel_id},function(data){
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
                    var inputStr = "&nbsp;&nbsp;"._AM_XMARTIN_NUMBER_OF_ROOMS.":<input type=text name=\"room_count_"+room_id+"\" value=1>";
                    jQuery("#rooms").append(Str + inputStr);
                }
            }
            </script>';
        $room        = new \XoopsFormElementTray($js . _AM_XMARTIN_SELECT_ROOMS . '<br>' . _AM_XMARTIN_FILTER_BY_HOTEL);
        $roomElement = new \XoopsFormSelect('', 'hotel_id', $this->hotelList, 5, false);
        $roomElement->addOptionArray($this->hotelList);
        //$roomElement->addOption('class','hotel');
        $room->addElement($roomElement, false);
        $room->addElement(new \XoopsFormElementTray('<br><br><div id="room"></div><div id="rooms">' . $roomStr . '</div>'), false);

        $this->addElement($room, false);

        $this->addElement(new \XoopsFormTextDateSelect(_AM_XMARTIN_CHECK_IN, 'check_in_date', $size = 15, $this->obj->check_in_date(), false), true);

        $this->addElement(new \XoopsFormTextDateSelect(_AM_XMARTIN_LAUNCH_TIME, 'check_out_date', $size = 15, $this->obj->check_out_date(), false), true);

        //$this->addElement( new \XoopsFormTextDateSelect(_AM_XMARTIN_START_BID_TIME, 'apply_start_date', $size = 15, $this->obj->apply_start_date(),false ) ,true);
        $this->addElement(new Xmartin\Form\FormDateTime(_AM_XMARTIN_START_BID_TIME, 'apply_start_date', $size = 15, $this->obj->apply_start_date()), true);
        //$this->addElement( new \XoopsFormTextDateSelect(_AM_XMARTIN_END_TIME_BIDDING, 'apply_end_date', $size = 15, $this->obj->apply_end_date() ) ,true);
        $this->addElement(new Xmartin\Form\FormDateTime(_AM_XMARTIN_END_TIME_BIDDING, 'apply_end_date', $size = 15, $this->obj->apply_end_date()), true);

        $this->addElement(new \XoopsFormText(_AM_XMARTIN_STARTING_PRICE, 'auction_price', 11, 11, $this->obj->auction_price()), true);
        $this->addElement(new \XoopsFormText(_AM_XMARTIN_CHEAP, 'auction_low_price', 11, 11, $this->obj->auction_low_price()), true);
        $this->addElement(new \XoopsFormText(_AM_XMARTIN_PRICE_MARKUP, 'auction_add_price', 11, 11, $this->obj->auction_add_price()), true);
        $this->addElement(new \XoopsFormText(_AM_XMARTIN_GIFT_VOUCHER . '?', 'auction_sented_coupon', 11, 11, (int)$this->obj->auction_sented_coupon()), true);
        $this->addElement(new \XoopsFormRadioYN(_AM_XMARTIN_CAN_YOU_USE_CASH_VOLUME, 'auction_can_use_coupon', $this->obj->auction_can_use_coupon(), _YES, _NO), true);
        $this->addElement(new \XoopsFormRadioYN(_AM_XMARTIN_STATUS, 'auction_status', $this->obj->auction_status(), _AM_XMARTIN_PUBLISHED, _AM_XMARTIN_DRAFT), true);

        $this->addElement(new \XoopsFormText(_AM_XMARTIN_TITLE, 'auction_name', 50, 255, $this->obj->auction_name()), true);
        $editor       = 'tinymce';
        $auction_info = $this->obj->auction_info();
        //var_dump($auction_info);
        $editor_configs           = [];
        $editor_configs['name']   = 'auction_info';
        $editor_configs['value']  = $auction_info;
        $editor_configs['rows']   = empty($helper->getConfig('editor_rows')) ? 35 : $helper->getConfig('editor_rows');
        $editor_configs['cols']   = empty($helper->getConfig('editor_cols')) ? 60 : $helper->getConfig('editor_cols');
        $editor_configs['width']  = empty($helper->getConfig('editor_width')) ? '100%' : $helper->getConfig('editor_width');
        $editor_configs['height'] = empty($helper->getConfig('editor_height')) ? '400px' : $helper->getConfig('editor_height');

        $this->addElement(new \XoopsFormEditor(_AM_XMARTIN_AUCTION_DETAILS, $editor, $editor_configs, false, $onfailure = null), false);
        $this->addElement(new \XoopsFormHidden('id', $this->obj->auction_id()));
    }

    /**
     * @创建按钮
     * @license   http://www.blags.org/
     * @created   :2010年05月20日 23时52分
     * @copyright 1997-2010 The Martin auction
     * @author    Martin <china.codehome@gmail.com>
     * */
    public function createButtons()
    {
        $buttonTray = new \XoopsFormElementTray('', '');
        // No ID for category -- then it's new category, button says 'Create'
        if (!$this->obj->auction_id()) {
            $butt_create = new \XoopsFormButton('', '', _RESET, 'submit');
            $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
            $buttonTray->addElement($butt_create);

            $butt_clear = new \XoopsFormButton('', '', _SUBMIT, 'reset');
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

            $butt_clear = new \XoopsFormButton('', '', _SUBMIT, 'reset');
            $buttonTray->addElement($butt_clear);

            $butt_cancel = new \XoopsFormButton('', '', _CANCEL, 'button');
            $butt_cancel->setExtra('onclick="history.go(-1)"');
            $buttonTray->addElement($butt_cancel);

            $this->addElement($buttonTray);
        }
    }
}
