<style type="text/css">@import "<{$module_url}>javascript/jquery/jquery-datepick/jquery.datepick.css";</style>
<script type="text/javascript" src="<{$module_url}>javascript/jquery/jquery-datepick/jquery.datepick.js"></script>
<script type="text/javascript" src="<{$module_url}>javascript/jquery/jquery-datepick/jquery.datepick-zh-CN.js"></script>
<script type="text/javascript" src="<{$module_url}>javascript/hotel.js"></script>
<link rel="stylesheet" href="<{$module_url}>css/hotelsearch.css" type="text/css">
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function ($) {
        $('.checkdate').datepick({minDate: 0, maxDate: 30, showOnFocus: false, dateFormat: 'yy-mm-dd'});
        $('.checkdate').change(function () {
            if (this.id == 'checkdate1' && $('#checkdate2').val() == '') {
                $('#checkdate2').focus();
            } else {
                $("#datepick-div").hide();
            }
            if (this.id == 'check_in_date' && $('#check_out_date').val() == '') {
                $('#check_out_date').focus();
            } else {
                $("#datepick-div").hide();
            }
        });
        $(".select_radio").click(function () {
            $(this).prev("input").attr("checked", true);
        });
    });
</script>
<div class="breadcrums"><span>您所在的位置：</span>首页</div>
<!--结束面包线-->
<div class="blocknh">
    <!--新闻-->
    <div class="leftblock">
        <form action="" method="POST" id="pay">
            <{securityToken}><{*//mb*}>
            <div class="hotellist">
                <div class="hoteltitle bookingtitle btop">
                    <div class="lefttitle">选择支付方式</div>
                </div>
                <div class="listclass">
                    <table width="100%" border="0" cellspacing="3" cellpadding="0">
                        <{if $order_pay_str}>
                            <tr>
                            <td height="60" colspan="2" bgcolor="#F4FFED" style="padding-left:15px;">
                                本订单原来选择的支付方式为：“<{$order_pay_str}>”，<br>
                                现准备更换支付方式为：
                            </td>    </tr><{/if}>
                        <tr>
                            <td width="26%" height="24">网上支付平台支付</td>
                            <td><{foreach from=$online_pays item=online key=key}><input value="<{$key}>" type="radio"
                                                                                        id="order_pay" name="order_pay"
                                                                                        <{if $key eq $order_pay}>checked="true"<{/if}>>
                                    <label class="select_radio"><{$online}></label><{/foreach}></td>
                        </tr>
                        <tr>
                            <td valign="top" style="padding-top:3px;">其他方式支付，支付到：</td>
                            <td>
                                <{foreach from=$line_pays item=line key=key}><p><input value="<{$key}>" type="radio"
                                                                                       id="order_pay" name="order_pay"
                                                                                       <{if $key eq $order_pay}>checked="true"<{/if}>><label
                                            class="select_radio"><{$line}></label></p><{/foreach}>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <input type="hidden" name="order_id" value="<{$order_id}>">
            <div style="margin:20px; font-weight:normal; text-align:center;"><input type="submit" value="支付"
                                                                                    style="margin-left:10px;"
                                                                                    class="button"></div>
        </form>
    </div>
    <!--新闻-->
    <!--首页酒店信息-->
    <div class="hotel"><{$martin_hotel_search_left}></div>
    <!--结束首页酒店信息-->
</div>
<!--结束主体--!>
