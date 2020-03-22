<style type="text/css">@import "<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick.css";</style>
<script type="text/javascript" src="<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick.js"></script>
<script type="text/javascript" src="<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick-zh-CN.js"></script>
<!--<script type="text/javascript" src="<{$module_url}>/javascript/jquery/msgbox/jquery.msgbox.min.js"></script>
<script type="text/javascript" src="<{$module_url}>/javascript/jquery/msgbox/msgbox.css"></script>-->

<script type="text/javascript" src="<{$module_url}>assets/javascript/hotel.js"></script>
<link rel="stylesheet" href="<{$module_url}>css/hotelsearch.css" type="text/css">
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function ($) {
        var AuctionMin = <{$auctiondate.min}>;
        var AuctionMax = <{$auctiondate.max}>;
        $('.checkdate').datepick({minDate: 0, maxDate: 30, showOnFocus: false, dateFormat: 'yy-mm-dd'});
        $('.checkdate').change(function () {
            if (this.id == 'checkdate1' && $('#checkdate2').val() == '')
                $('#checkdate2').focus();
            else
                $("#datepick-div").hide();
        });
        $(".roomCheck").datepick({
            minDate: AuctionMin,
            maxDate: AuctionMax,
            showOnFocus: false,
            dateFormat: 'yy-mm-dd'
        });
        $('.roomCheck').change(function () {
            if (this.id == 'Check_in_date' && $('#Check_out_date').val() == '')
                $('#Check_out_date').focus();
            else
                $("#datepick-div").hide();
        });
        $("#subform").click(function () {
            var CheckIn = $('#Check_in_date').val();
            var CheckOut = $('#Check_out_date').val();
            var auction_price = Number($('#auction_price').val());
            var RoomCount = Number($('#RoomCount').val());
            var Auction_add_price = Number($('#auction_add_price').val());
            var AuctionPrice = Number($('#AuctionPrice').val());
            var RoomCountOld = Number($('#RoomCountOld').val());
            var result = true;
            $('#auction input').each(function (i) {
                if ($.trim($(this).val()) == '') {
                    $(this).focus();
                    result = false;
                    alert('此项必填.');
                    return false;
                }
            });
            if (!result) return false;
            if (RoomCount <= 0 || RoomCount > RoomCountOld) {
                $('#RoomCount').focus();
                result = false;
                alert('房间数无效.');
                return false;
            }
            if (compareDate(CheckIn, CheckOut)) {
                alert('时间无效.');
                result = false;
                return false;
            }
            if (AuctionPrice < Number(auction_price + Auction_add_price)) {
                $('#AuctionPrice').focus();
                result = false;
                alert('出价无效.');
                return false;
            }
            if (result) $('#auction').submit();
        });
    });
</script>
<div class="breadcrums"><span>您所在的位置：</span>首页</div>
<!--结束面包线-->
<div class="blocknh">
    <!--新闻-->
    <div class="leftblock">
        <div class="l_title"><{$auction.auction_name}></div>
        <div class="hotellist">

            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                <tr align="center" style="border: none;">
                    <td colspan="4" align="left" style="padding:15px;">
                        <p><{$auction.auction_info|htmlspecialchars_decode}></p></td>
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
                    <td width="32%" align="left"><{$auction.check_in_date|date_format:"%Y年%m月%d日"}></td>
                    <td width="20%" align="right">退房日期：</td>
                    <td width="32%" align="left"><{$auction.check_out_date|date_format:"%Y年%m月%d日"}></td>
                </tr>
                <tr align="center" style="border: none;">
                    <td width="20%" align="right">开始报名日期：</td>
                    <td width="32%" align="left"><{$auction.apply_start_date|date_format:"%Y年%m月%d日"}></td>
                    <td width="20%" align="right">截止报名日期：</td>
                    <td width="32%" align="left"><{$auction.apply_end_date|date_format:"%Y年%m月%d日"}></td>
                </tr>
                <tr align="center" style="border: none;">
                    <td width="20%" align="right"><{$smarty.const._AM_XMARTIN_STARTING_PRICE}>：</td>
                    <td width="32%" align="left"><span class="red"><{$auction.auction_low_price}></span> 元</td>
                    <td width="20%" align="right">加价辐度至少为：</td>
                    <td width="32%" align="left"><span class="red"><{$auction.auction_add_price}></span> 元</td>
                </tr>
                <tr align="center" style="border: none;">
                    <td width="20%" align="right">赚送现金券额度：</td>
                    <td width="32%" align="left"><span class="red"><{$auction.auction_sented_coupon}></span> 元</td>
                    <td width="20%" align="right">是否允许使用现金券抵扣房款：</td>
                    <td width="32%" align="left"><{if $auction.auction_can_use_coupon}>允许<{else}>不充许<{/if}></td>
                </tr>
                <tr align="center" style="border: none;">
                    <td width="20%" align="right">房源总量：</td>
                    <td colspan="3" align="left"><span class="red"><{$roomcount}></span></td>
                </tr>
                <tr align="center" style="border: none;">
                    <td colspan="4" align="left" class="tdnone" style="padding-left:15px;">&nbsp;</td>
                </tr>
            </table>
        </div>
        <div class="hotellist">
            <form action="?action=save" id="auction" method="POST">
                <{securityToken}><{*//mb*}>
                <div class="hoteltitle bookingtitle">
                    <div class="lefttitle">我要出价（可自由选择入住日期与退房日期时段内的房源）</div>
                </div>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                    <tr>
                        <td width="13%" align="right">_AM_XMARTIN_CHECK_IN：</td>
                        <td width="37%" align="left">
                            <input type="text" value="<{$auction.check_in_date|date_format:"%Y-%m-%d"}>"
                                   class="roomCheck" id="Check_in_date" name="Check_in_date" readonly="true">
                        </td>
                        <td width="13%" align="right">_AM_XMARTIN_CHECK_OUT：</td>
                        <td width="37%" align="left">
                            <input type="text" value="<{$auction.check_out_date|date_format:"%Y-%m-%d"}>"
                                   class="roomCheck" id="Check_out_date" name="Check_out_date" readonly="true">
                        </td>
                    </tr>
                    <tr>
                        <td width="13%" align="right" class="tdnone">数量：</td>
                        <td width="37%" align="left" class="tdnone"><input name="RoomCount" id="RoomCount" type="text"
                                                                           size="5"></td>
                        <td width="13%" align="right" class="tdnone">出价：</td>
                        <td width="37%" align="left" class="tdnone">
                            <input name="AuctionPrice" id="AuctionPrice" type="text" size="5">
                            <input type="hidden" name="auction_id" value="<{$auction_id}>">
                            <input type="hidden" id="auction_add_price" value="<{$auction.auction_add_price}>">
                            <input type="hidden" id="auction_price" value="<{$auction.auction_low_price}>">
                            <input type="hidden" id="RoomCountOld" value="<{$roomcount}>">
                        </td>
                    </tr>
                </table>
                <div style="margin:20px; overflow:hidden; font-weight:normal; text-align:center;"><input type="button"
                                                                                                         id="subform"
                                                                                                         value="确定出价"
                                                                                                         style="margin-left:10px;"
                                                                                                         class="button">
                </div>
            </form>
        </div>
        <{if $bids}>
            <div class="hotellist" style="margin-bottom:20px;">
                <div class="hoteltitle bookingtitle btop">
                    <div class="lefttitle">已有出价</div>
                </div>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                    <tr align="center" class="fwt">
                        <td width="13%">会员</td>
                        <td width="16%">出价</td>
                        <td width="22%">出价时间</td>
                        <td width="14%"><p>入住日期</p></td>
                        <td width="15%">退房日期</td>
                        <td width="12%">数量</td>
                        <td width="8%">状态</td>
                    </tr>
                    <{foreach item=bid name=bid from=$bids}>
                        <tr align="center">
                            <td style="border: none;"><a
                                        href="<{$xoops_url}>/userinfo.php?uid=<{$bid.uid}>"><{$bid.uname}></a></td>
                            <td style="border: none;"><{$bid.bid_price}></td>
                            <td style="border: none;"><{$bid.bid_time|date_format:"%Y-%m-%d %H:%M:%S"}></td>
                            <td style="border: none;"><{$bid.check_in_time|date_format:"%Y-%m-%d"}></td>
                            <td style="border: none;"><{$bid.check_in_time|date_format:"%Y-%m-%d"}></td>
                            <td style="border: none;"><{$bid.bid_count}></td>
                            <td style="border: none;"><{if $smarty.foreach.bid.first}>领先<{else}>暂定<{/if}></td>
                        </tr>
                    <{/foreach}>
                </table>
            </div>
        <{else}>
            <div class="hotellist" style="margin-bottom:20px;">
                <div class="hoteltitle bookingtitle btop">
                    <div class="lefttitle">暂无出价，抢购吧.</div>
                </div>
            </div>
        <{/if}>
    </div>
    <!--新闻-->
    <!--首页酒店信息-->
    <div class="hotel">
        <{$martin_hotel_search_left}>
    </div>
    <!--结束首页酒店信息-->
</div>
