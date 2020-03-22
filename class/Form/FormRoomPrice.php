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
 * Class FormRoomPrice
 */
class FormRoomPrice extends \XoopsThemeForm
{
    /**
     * FormRoomPrice constructor.
     * @param $roomPriceObj
     * @param $roomList
     */
    public function __construct($roomPriceObj, $roomList)
    {
        $this->obj      = $roomPriceObj;
        $this->roomList = $roomList;
        parent::__construct(_AM_XMARTIN_HOTEL_ROOM_RATES, 'op', xoops_getenv('SCRIPT_NAME') . '?action=pricesave&room_id=' . @$roomPriceObj['room_id'] . '&room_date=' . date('Y-m-d', @$roomPriceObj['room_date']));
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
        $Price     = $this->obj;
        $today     = (int)date('d');
        $MouthDays = (int)$this->MouthDays(date('m'));

        $js         = '<script type=\'text/javascript\'>
            jQuery.noConflict();
            jQuery(document).ready(function($){
                $("#sub").click(function(){
                    var result = true;
                    $("form input[type=text]").each(function(Element){
                        if($.trim($(this).val()) == "" && $.trim($(this).attr("class")) != "")
                        {
                            $(this).focus();
                            result = false;
                            return false;
                        }
                    });
                    if(result)
                    {
                        $("#op").submit();
                    }else{
                        alert("只能数字,不能为空");
                    }
                });
                $(".sel").click(function(){
                    var Check = $(this).prev("input[type=checkbox]").attr("checked") ? false : true;
                    $(this).prev("input[type=checkbox]").attr("checked",Check);
                });
                /*$(".even").parent("tr").click(function(){
                    var Check = $(this).find("input[type=checkbox]").attr("checked") ? false : true;
                    $(this).find("input[type=checkbox]").attr("checked",Check);
                    $(this).attr("style","background-color: rgb(0, 255, 0);");
                });*/
                $("#room_price_all").click(function(){
                    var value = $(this).next("input").val();
                    if(!parseFloat(value)){alert("this must be Number.");$(this).focus();return false;}
                    $(".room_price").val(value);
                });
                $("#room_advisory_range_max_all").click(function(){
                    var value = $(this).next("input").val();
                    if(!parseFloat(value)){alert("this must be Number.");$(this).focus();return false;}
                    $(".room_advisory_range_max").val(value);
                });
                $("#room_advisory_range_min_all").click(function(){
                    var value = $(this).next("input").val();
                    if(!parseFloat(value)){alert("this must be Number.");$(this).focus();return false;}
                    $(".room_advisory_range_small").val(value);
                });
                $("#room_sented_coupon_all").click(function(){
                    var value = $(this).next("input").val();
                    if(!parseFloat(value)){alert("this must be Number.");$(this).focus();return false;}
                    $(".room_sented_coupon").val(value);
                });
            });
            </script>';
        $button_str = '
                        <table><tr><td width="150px;align=center;"><input type="button" id="room_price_all" value="ALL">&nbsp;<input type="text" size=5></td>
                        <td width="100px;align=center;"><input type="button" id="room_advisory_range_min_all" value="ALL">&nbsp;<input type="text" size=5></td>
                        <td width="150px;align=center;"><input type="button" id="room_advisory_range_max_all" value="ALL">&nbsp;<input type="text" size=5></td>
                        <td width="150px;align=center;"><input type="button" id="room_sented_coupon_all" value="ALL">&nbsp;<input type="text" size=5></td></tr></table>
                    ';
        //var_dump($Price);
        if (!empty($Price) && is_array($Price) && isset($Price['room_price'])) {
            $PriceArea   = new \XoopsFormElementTray($js . '房间价格');
            $roomElement = new \XoopsFormSelect(_AM_XMARTIN_GUEST_ROOM, 'room_id', $Price['room_id'], 1);
            $roomElement->addOptionArray($this->roomList);
            $PriceArea->addElement($roomElement, true);
            $PriceArea->addElement(new \XoopsFormText(_AM_XMARTIN_PRICE . ':', 'room_price', 11, 11, $Price['room_price']), true);
            $PriceArea->addElement(new \XoopsFormText(_AM_XMARTIN_PRICE_RANGE . ':', 'room_advisory_range_small', 11, 11, $Price['room_advisory_range_small']), true);
            $PriceArea->addElement(new \XoopsFormText('-', 'room_advisory_range_max', 11, 11, $Price['room_advisory_range_max']), true);
            //_AM_XMARTIN_GIFT_VOUCHER
            $PriceArea->addElement(new \XoopsFormText(_AM_XMARTIN_GIFT_VOUCHER . ':', 'room_sented_coupon', 11, 11, $Price['room_sented_coupon']), true);
            $Special = new \XoopsFormCheckBox('', 'room_is_today_special', $Price['room_is_today_special']);
            $Special->addOption(1, '<label for="room_is_today_special">' . _YES . ' ' . _AM_XMARTIN_LAST_MINUTE . '</label>');
            //$PriceArea->addElement(new \XoopsFormText('时间:', 'room_date', 11, 11, date("Y-m-d",$Price['room_date'])), true);
            $PriceArea->addElement(new \XoopsFormHidden('room_date[]', $Price['room_date']));
            $PriceArea->addElement($Special, false);
            $this->addElement($PriceArea, false);
        } else {
            $roomElement = new \XoopsFormSelect($js . _AM_XMARTIN_GUEST_ROOM . '<br><span style="color:red"><b>' . _AM_XMARTIN_DO_NOT_MODIFY_TIME . '</b></span>', 'room_id', '', 1);
            $roomElement->addOptionArray($this->roomList);
            $this->addElement($roomElement);
            $Select = new \XoopsFormElementTray(_AM_XMARTIN_BATCH_PROCESSING);
            $Select->addElement(new \XoopsFormLabel('', $button_str));

            $this->addElement($Select);

            //var_dump($Price[1277395200]);
            //$countDate = count($Price) > 0 ? count($Price) + $today : $MouthDays + $today;
            $countDate = $MouthDays + $today;
            for ($today; $today <= $countDate; $today++) {
                $date     = ($today > $MouthDays) ? date('Y') . '-' . (date('m') + 1) . '-' . ($today - $MouthDays) : date('Y') . '-' . date('m') . '-' . $today;
                $dateTime = strtotime($date);

                //var_dump($Price[$dateTime]);
                //                ${"PriceArea" . $today} = new \XoopsFormElementTray(_AM_XMARTIN_ROOM . $date . ' ' . _AM_XMARTIN_PRICE_ON);
                ${'PriceArea' . $today} = new \XoopsFormElementTray(_AM_XMARTIN_ROOM_PRICE_ON_DATE . $date);
                $room_price             = new \XoopsFormText(_AM_XMARTIN_PRICE . ':', 'room_price[]', 11, 11, isset($Price[$dateTime]) ? $Price[$dateTime]['room_price'] : '0.00');
                $room_price->setExtra("class='room_price'");
                ${'PriceArea' . $today}->addElement($room_price, true);

                $room_advisory_range_small = new \XoopsFormText(_AM_XMARTIN_PRICE_RANGE . ':', 'room_advisory_range_small[]', 11, 11, isset($Price[$dateTime]) ? $Price[$dateTime]['room_advisory_range_small'] : '0.00');
                $room_advisory_range_small->setExtra("class='room_advisory_range_small'");
                ${'PriceArea' . $today}->addElement($room_advisory_range_small, true);

                $room_advisory_range_max = new \XoopsFormText('-', 'room_advisory_range_max[]', 11, 11, isset($Price[$dateTime]) ? $Price[$dateTime]['room_advisory_range_max'] : '0.00');
                $room_advisory_range_max->setExtra("class='room_advisory_range_max'");
                ${'PriceArea' . $today}->addElement($room_advisory_range_max, true);
                //_AM_XMARTIN_GIFT_VOUCHER
                $room_sented_coupon = new \XoopsFormText(_AM_XMARTIN_GIFT_VOUCHER . ':', 'room_sented_coupon[]', 11, 11, isset($Price[$dateTime]) ? $Price[$dateTime]['room_sented_coupon'] : '0.00');
                $room_sented_coupon->setExtra("class='room_sented_coupon'");
                ${'PriceArea' . $today}->addElement($room_sented_coupon, true);

                $Special = new \XoopsFormCheckBox('', "room_is_today_special[$dateTime]", isset($Price[$dateTime]) ? $Price[$dateTime]['room_is_today_special'] : 0);
                $Special->addOption(1, '<label class="sel">' . _YES . ' ' . _AM_XMARTIN_LAST_MINUTE . '</label>');
                //echo isset($Price[$dateTime]) ? $Price[$dateTime]['room_is_today_special'] : 0;echo '<br>';
                //hide time
                //${"PriceArea".$today}->addElement(new \XoopsFormText('时间:', 'room_date[]', 11, 11, $date), true);
                ${'PriceArea' . $today}->addElement(new \XoopsFormHidden('room_date[]', $date));
                ${'PriceArea' . $today}->addElement($Special, false);
                $this->addElement(${'PriceArea' . $today}, false);
                unset(${'PriceArea' . $today}, $dateTime, $date, $room_price, $room_advisory_range_max, $room_advisory_range_small, $room_sented_coupon, $Special);
            }
        }
        /*$this->addElement( new \XoopsFormText(_AM_XMARTIN_ROOM_NAME, 'room_name', 45, 45, $this->obj->room_name()), true);
        $this->addElement( new \XoopsFormText(_AM_XMARTIN_ROOMS_AREA, 'room_area', 11, 11, $this->obj->room_area()), true);
        $this->addElement( new \XoopsFormText(_AM_XMARTIN_ROOM_FLOOR, 'room_floor', 45, 45, $this->obj->room_floor()), true);

        $this->addElement( new \XoopsFormText('加价', 'room_add_money', 11, 11, $this->obj->room_add_money()), false);
        $this->addElement( new \XoopsFormTextArea('床描述', 'room_bed_info', $this->obj->room_bed_info()) , false);
        $this->addElement( new \XoopsFormRadioYN(_AM_XMARTIN_ROOM_STATUS, 'room_status', $this->obj->room_status(), _AM_XMARTIN_PUBLISHED,  _AM_XMARTIN_DRAFT) , true);
        $this->addElement( new \XoopsFormText(_AM_XMARTIN_GIFT_VOUCHER, 'room_sented_coupon', 11, 11, (int)($this->obj->room_sented_coupon())), false);*/
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
            $butt_create = new \XoopsFormButton('', 'sub', _SUBMIT, 'button');
            //$butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
            $buttonTray->addElement($butt_create);

            $butt_clear = new \XoopsFormButton('', '', _RESET, 'reset');
            $buttonTray->addElement($butt_clear);

            $butt_cancel = new \XoopsFormButton('', '', _CANCEL, 'button');
            $butt_cancel->setExtra('onclick="history.go(-1)"');
            $buttonTray->addElement($butt_cancel);

            $this->addElement($buttonTray);
        } else {
            // button says 'Update'
            $butt_create = new \XoopsFormButton('', 'sub', _EDIT, 'button');
            //$butt_create->setExtra('onclick="this.form.elements.op.value=\'addcategory\'"');
            $buttonTray->addElement($butt_create);

            $butt_clear = new \XoopsFormButton('', '', _RESET, 'reset');
            $buttonTray->addElement($butt_clear);

            $butt_cancel = new \XoopsFormButton('', '', _CANCEL, 'button');
            $butt_cancel->setExtra('onclick="history.go(-1)"');
            $buttonTray->addElement($butt_cancel);

            $this->addElement($buttonTray);
        }
    }

    /**
     * @param $mouth
     * @return array|bool|int|string
     */
    public function MouthDays($mouth)
    {
        $date     = date('Y') . '-' . $mouth . '-' . date('d');
        $firstday = date('Y-m-01', strtotime($date));
        $lastday  = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
        $lastday  = explode('-', $lastday);
        $lastday  = array_reverse($lastday);
        $lastday  = (int)$lastday[0];

        return $lastday;
    }
}
