<style type="text/css">@import "<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick.css";</style>
<script type="text/javascript" src="<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick.js"></script>
<script type="text/javascript" src="<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick-zh-CN.js"></script>
<script type="text/javascript" src="<{$module_url}>assets/javascript/hotel.js"></script>
<link rel="stylesheet" href="<{$module_url}>css/hotelsearch.css" type="text/css">
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function ($) {
        $('.checkdate').datepick({minDate: 0, maxDate: 30, showOnFocus: false, dateFormat: 'yy-mm-dd'});
        $('.checkdate').change(function () {
            if (this.id == 'checkdate1' && $('#checkdate2').val() == '')
                $('#checkdate2').focus();
            else
                $("#datepick-div").hide();
        });
    });
</script>

<div class="breadcrums"><span>您所在的位置：</span>首页</div>
<!--结束面包线-->
<div class="blocknh">
    <!--新闻-->
    <div class="leftblock">
        <div class="l_title"><{$group.group_name}></div>
        <div class="hotellist">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                <tr align="center" style="border: none;">
                    <td colspan="4" align="left" style="padding:15px;">
                        <!--<img src="images/70.jpg" style="float:left; padding-right:10px;"><strong>上海恒硕公寓酒店</strong>-->
                        <p><{$group.group_info|htmlspecialchars_decode}></p></td>
                </tr>
                <{foreach item=room from=$rooms}>
                    <{assign var=roomcount value=$room.room_count+$roomcount}>
                    <tr align="center" style="border: none;">
                        <td width="20%" align="right"><{$smarty.const._AM_XMARTIN_HOTEL_NAME}>：</td>
                        <td width="32%" align="left"><a
                                    href="<{$xoops_url}>/hotel-<{$room.hotel_alias}><{$hotel_static_prefix}>"><{$room.hotel_name}></a>
                        </td>
                        <td width="20%" align="right">房型：</td>
                        <td width="32%" align="left"><{$room.room_type_info}></td>
                    </tr>
                <{/foreach}>
                <tr align="center" style="border: none;">
                    <td width="20%" align="right">入住日期：</td>
                    <td width="32%" align="left"><{$group.check_in_date|date_format:"%Y年%m月%d日"}></td>
                    <td width="20%" align="right">退房日期：</td>
                    <td width="32%" align="left"><{$group.check_out_date|date_format:"%Y年%m月%d日"}></td>
                </tr>
                <tr align="center" style="border: none;">
                    <td width="20%" align="right">开始报名日期：</td>
                    <td width="32%" align="left"><{$group.apply_start_date|date_format:"%Y年%m月%d日"}></td>
                    <td width="20%" align="right">截止报名日期：</td>
                    <td width="32%" align="left"><{$group.apply_end_date|date_format:"%Y年%m月%d日"}></td>
                </tr>
                <tr align="center" style="border: none;">
                    <td width="20%" align="right">团购价：</td>
                    <td width="32%" align="left"><span class="red"><{$group.group_price}></span> 元</td>
                    <td width="20%" align="right">房源总量：</td>
                    <td width="32%" align="left"><span class="red"><{$roomcount}></span></td>
                </tr>
                <tr align="center" style="border: none;">
                    <td width="20%" align="right">赠送现金券面额：</td>
                    <td width="32%" align="left"><span class="red"><{$group.group_sented_coupon}></span> 元</td>
                    <td width="20%" align="right">是否允许使用现金券抵扣房款：</td>
                    <td width="32%" align="left"><{if $group.group_can_use_coupon}>允许<{else}>不充许<{/if}></td>
                </tr>

                <tr align="center" style="border: none;">
                    <td colspan="4" align="left" class="tdnone" style="padding-left:15px;">&nbsp;</td>
                </tr>
            </table>
        </div>
        <div style="margin:20px; overflow:hidden; font-weight:normal; text-align:center;">
            <form action="group.php?action=save" method="POST" id="group">
                <{securityToken}><{*//mb*}>
                <input type="hidden" name="group_id" value="<{$group_id}>">
                <input type="submit" value="Register" style="margin-left:10px;" class="button">
            </form>
        </div>
    </div>
    <!--新闻-->
    <!--首页酒店信息-->
    <div class="hotel">
        <{$martin_hotel_search_left}>
    </div>
    <!--结束首页酒店信息-->
</div>

<!--结束主体--!>
