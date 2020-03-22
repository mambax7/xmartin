<!--面包线-->
<link rel="stylesheet" href="<{$module_url}>css/hotel.css" type="text/css">
<style type="text/css">@import "<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick.css";</style>
<script type="text/javascript" src="<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick.js"></script>
<script type="text/javascript" src="<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick-zh-CN.js"></script>
<script type="text/javascript" src="<{$module_url}>assets/javascript/hotel.js"></script>
<script type="text/javascript">
    var Online_Url = "<{$module_config.online_url}>";
    jQuery.noConflict();
    jQuery(document).ready(function ($) {
        $('.checkdate').datepick({minDate: 0, maxDate: 60, showOnFocus: false, dateFormat: 'yy-mm-dd'});
        $('.checkdate').change(function () {
            if (this.id == 'checkdate1' && $('#checkdate2').val() == '')
                $('#checkdate2').focus();
            else
                $("#datepick-div").hide();
        });
        $("#printOrder").click(function () {
            var html = '<link rel="stylesheet" href="<{$module_url}>css/hotel.css" type="text/css">';
            /*$(".hotellist").each(function(){
             html += $(this).html();
             });*/
            //html += $(".blocknh").not(".hotel").html();
            window.print();
            //doPrint(html);
        });
    });
</script>
<div class="breadcrums"><span>您所在的位置：<a href="<{$xoops_url}>">首页<a></span> <img width="3" height="5"
                                                                                src="<{$news_url}>images/arrow.png">
    订单 <{$order_id}> 详情
</div>
<!--结束面包线-->
<div class="blocknh">
    <!--新闻-->
    <div class="leftblock" id="orderInfo">
        <div class="l_title">选择 <{$hotel_name}> - <{$room_name}> &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)"
                                                                                            id="printOrder">订单详情打印</a>
        </div>
        <div class="hotellist">
            <div class="hoteltitle bookingtitle btop">
                <div class="lefttitle">入住日期选择</div>
            </div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                <tr align="center" class="fwt">
                    <td width="23%">日期</td>
                    <td width="17%">价格</td>
                    <td width="10%">数量</td>
                    <td width="15%">房费小计</td>
                    <td width="20%">赠送现金券</td>
                    <td width="25%">现金券小计</td>
                </tr>
                <{assign var=total value=0}>
                <{foreach from=$rooms item=room}>
                    <{assign var=total value=$total+$room.room_price}>
                    <tr align="center">
                        <td width="23%"><{$room.room_date|date_format:"%Y年%m月%d日"}></td>
                        <td width="17%"><{if $room.room_price > 0}><{$room.room_price}><{else}>查询中<{/if}></td>
                        <td width="10%"><{$room.room_count}></td>
                        <td width="15%"><{$room.room_price*$room.room_count|string_format:"%.2f"}></td>
                        <td width="20%"><{$room.room_sented_coupon|string_format:"%.2f"}></td>
                        <td width="25%"><{$room.room_sented_coupon*$room.room_count}></td>
                    </tr>
                <{/foreach}>
                <tr align="center" style="border: none;">
                    <td colspan="4" align="left" style="padding-left:15px;" class="tdnone">房费合计：<span
                                class="red"><{$total|string_format:"%.2f"}></span>元
                    </td>
                <tr align="center">
                    <td colspan="5" align="left" style="padding-left:15px;" class="tdnone">赠现金券金额合计：<span
                                class="red send_price"><{$order.order_sented_coupon}></span>元
                    </td>
                </tr>
                </tr>
            </table>
        </div>
        <div class="hotellist">
            <div class="hoteltitle bookingtitle">
                <div class="lefttitle">附属服务</div>
            </div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                <tr align="center" class="fwt">
                    <td width="27%">附属服务名称</td>
                    <td width="19%">单位</td>
                    <td width="18%">价格</td>
                    <td width="17%"><p>数量</p></td>
                    <td width="19%">附属服务小计</td>
                </tr>
                <{assign var=s_total value=0}>
                <{foreach from=$services item=service}>
                    <{assign var=s_s_total value=$service.service_count*$service.service_extra_price}>
                    <{assign var=s_total value=$s_total+$s_s_total}>
                    <tr align="center">
                        <td width="27%"><{$service.service_type_name}></td>
                        <td width="19%"><{$service.service_unit}></td>
                        <td width="18%"><{$service.service_extra_price}></td>
                        <td width="17%"><{$service.service_count}></td>
                        <td width="19%"><{$service.service_count*$service.service_extra_price|string_format:"%.2f"}></td>
                    </tr>
                <{/foreach}>
                <tr align="center">
                    <td colspan="5" align="left" style="padding-left:15px;" class="tdnone">附属服务合计：<span
                                class="red"><{$s_total|string_format:"%.2f"}></span>元
                    </td>
                </tr>
            </table>
        </div>
        <div class="hotellist">
            <div class="hoteltitle bookingtitle">
                <div class="lefttitle">结算</div>
            </div>
            <div class="listclass">
                1、本订房单房费为人民币<span class="red"><{$total|string_format:"%.2f"}></span>元，附属服务费为人民币为<span
                        class="red"><{$s_total|string_format:"%.2f"}></span>元，您要兑换掉的现金券为人民币<span
                        class="red"><{$order.order_coupon}></span>元，您需要实际支付的金额为人民币<span
                        class="red"><{$order.order_pay_money}></span>元；<br>
                2、通过本次预订，您将获赠现金券金额为人民币<span class="red"><{$order.order_sented_coupon}></span>元，将于您退房日期后一个工作日内到帐，注意查收；
            </div>
        </div>

        <div class="hotellist">
            <div class="hoteltitle bookingtitle">
                <div class="lefttitle">港捷旅赠品</div>
            </div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                <tr>
                    <td><{$promotion.promotion_description}></td>
                </tr>
            </table>
        </div>
        <div class="hotellist">
            <div class="hoteltitle bookingtitle">
                <div class="lefttitle">酒店特别提示</div>
            </div>
            <div class="listclass"><{$hotel_info->hotel_reminded()}></div>
        </div>
        <div class="hotellist">
            <div class="hoteltitle bookingtitle">
                <div class="lefttitle">办理入住人信息</div>
            </div>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                <tr>
                    <td width="18%" align="right">姓名：</td>
                    <td width="32%"><{$order.order_real_name}></td>
                    <td width="18%" align="right">证件类型：</td>
                    <td width="32%"><{$order_document_type[$order.order_document_type]}> </td>
                </tr>
                <tr>
                    <td align="right">固定电话：</td>
                    <td> <{$order.order_telephone}> </td>
                    <td align="right">证件号码：</td>
                    <td><{$order.order_document}></td>
                </tr>
                <tr>
                    <td align="right">手机：</td>
                    <td> <{$order.order_phone}> </td>
                    <td></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td rowspan="3" align="right">其他入住人姓名：</td>
                    <td colspan="4" align="center" class="tdnone"><{$order.order_extra_persons}></td>
                </tr>
            </table>
        </div>
        <div class="hotellist">
            <div class="hoteltitle bookingtitle">
                <div class="lefttitle">支付方式</div>
            </div>
            <div class="listclass"><{$pays[$order.order_pay]}></div>
        </div>
        <div style="margin:20px; font-weight:normal; text-align:center;">
            <!--新闻-->
            <!--首页酒店信息-->
        </div>
    </div>
    <div class="hotel"><{$martin_hotel_search_left}></div>
    <!--结束首页酒店信息-->
</div>
