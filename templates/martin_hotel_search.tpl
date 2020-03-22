<style type="text/css">@import "<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick.css";</style>
<script type="text/javascript" src="<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick.js"></script>
<script type="text/javascript" src="<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick-zh-CN.js"></script>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<{$googleApi}>" type="text/javascript"></script>
<script type="text/javascript" src="<{$module_url}>assets/javascript/hotel.js"></script>
<link rel="stylesheet" href="<{$module_url}>css/hotelsearch.css" type="text/css">
<script type="text/javascript">
    var window_width = screen.width;
    var left_width = Number(window_width - <{$google_w_h.0}>) / 2;
    jQuery.noConflict();
    jQuery(document).ready(function ($) {
        $('.checkdate').datepick({minDate: 0, maxDate: 30, showOnFocus: false, dateFormat: 'yy-mm-dd'});
        $('.checkdate').change(function () {
            if (this.id == 'checkdate1' && $('#checkdate2').val() == '')
                $('#checkdate2').focus();
            else
                $("#datepick-div").hide();
        });
        $('.rela').hover(function () {
            $('.abso').each(function () {
                $(this).hide();
            });
            $(this).find(".abso").show();
        });
        $('.abso').hover(function () {
        }, function () {
            $(this).hide();
        });
    });
    function showHotel(ele,
    class)
    {
        if (jQuery("." + class).hasClass('show')) {
            jQuery(ele).text("全部房型 ▼");
            jQuery("." + class).hide();
            jQuery("." + class).removeClass('show');
        } else {
            jQuery(ele).text("全部房型 ▲");
            jQuery("." + class).show();
            jQuery("." + class).addClass('show');
        }
    }
    function ShowMap(even, Lat, Lng, hotel_name) {
        //jQuery("#lat").val(Lat);jQuery("#lng").val(Lng);
        //var lat = Lat;var lng = Lng;
        var ImgStr = '';
        var hotel_name = [hotel_name];
        var message = jQuery(even).parents("div .topright").find("p").next("p").text();
        var message = [message];
        jQuery("#mapbig").css('left', left_width + 'px');
        jQuery(".mapbig").show();
        jQuery("#mapClose").css('left', left_width + <{$google_w_h.0}>  +15 + 'px');
        jQuery("#mapClose").show();
        initialize('mapbig',<{$google_w_h.0}>,<{$google_w_h.1}>, true, Lat, Lng, hotel_name, message, ImgStr);
    }
    function showPrice(ele) {
        if (jQuery(ele).next('div').hasClass('show')) {
            jQuery(ele).next('div').hide();
            jQuery(ele).next('div').removeClass('show');
        } else {
            jQuery(ele).next('div').show();
            jQuery(ele).next('div').addClass('show');
        }
    }
</script>

<{assign var=star value='images/star.gif'}>
<{assign var=star value="<img src='$module_url$star'>"}>
<{assign var=up_down value='images/u_n.gif'}>
<{assign var=up_down value="<img src='$module_url$up_down' width='12' height='16' style='float:left; padding:3px 3px 0 0;'>"}>
<{assign var=order_str value="images/$by.gif"}>
<{assign var=order_str value="<img src='$module_url$order_str' style='margin-top:5px; margin-right:3px;'>"}>
<div class="breadcrums"><span>您所在的位置：</span>首页</div>
<!--结束面包线-->
<div class="blocknh">
    <!--新闻-->
    <div class="leftblock">
        <div class="l_title">选择（<{$select_title}>）<{$smarty.const._AM_XMARTIN_PROMO_HOTELS}></div>
        <div class="hotellist">
            <div class="hoteltitle">
                <div class="left"><{$smarty.const._AM_XMARTIN_HOTEL_LIST}></div>
                <div class="right">
                    <{if $next_url}><a href="<{$next_url}>"><img src="<{$module_url}>images/next.gif" border="0">
                        </a><{/if}>
                    <{if $prev_url}><a href="<{$prev_url}>"><img src="<{$module_url}>images/nav.gif" border="0">
                        </a><{/if}>
                    <{if $check_date.0}>
                        <span>入住日期：</span>
                        <{$check_date.0}>
                        <span>离店日期：</span>
                        <{$check_date.1}> &nbsp;共<{$check_in_date_count}>晚<{/if}></div>
            </div>
            <div class="listtitle">
                <ul>
                    <li class="titleleft"><{$up_down}>排序方式:</li>
                    <li><{$up_down}><a
                                href="<{$this_url}>&amp;Order=room_price&amp;By=<{$this_by}>"><{$smarty.const._AM_XMARTIN_PRICE}> <{if $order eq "room_price"}><{$order_str}><{/if}></a>
                    </li>
                    <li><{$up_down}><a
                                href="<{$this_url}>&amp;Order=hotel_star&amp;By=<{$this_by}>"><{$smarty.const._AM_XMARTIN_HOTEL_STARS}> <{if $order eq "hotel_star"}><{$order_str}><{/if}></a>
                    </li>
                    <li><{$up_down}><a
                                href="<{$this_url}>&amp;Order=room_is_today_special&amp;By=<{$this_by}>">今日特价<{if $order eq "room_is_today_special"}><{$order_str}><{/if}></a>
                    </li>
                    <li>共<{$count}>家酒店</li>
                </ul>
            </div>
            <{foreach item=hotel name=hotel from=$hotels}>
                <div class="listclass">
                    <div class="listtop">
                        <div class="topleft"><img src="<{$module_url}>img.php?id=<{$hotel.hotel_icon}>&w=70&h=70"
                                                  title="<{$hotel.hotel_name}>"></div>
                        <div class="topright">
                            <p class="title"><a
                                        href="<{$xoops_url}>/hotel-<{$hotel.hotel_alias}><{$hotel_static_prefix}><{if $check_in_date_count}><{$check_date_str}><{/if}>"><{$hotel.hotel_name}></a>（<{$hotel.hotel_enname}>
                                ）
                                <{$star|str_repeat:$hotel.hotel_star}>
                            </p>
                            <p><{$hotel.hotel_description}></p>
                            <p>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td width="15%"><img src="<{$module_url}>images/icon_03.gif"
                                                         style="float:left; margin-top:5px; margin-right:3px;"
                                                         width="18" height="18">行政区：
                                    </td>
                                    <td width="14%"><{$hotel.hotel_city}></td>
                                    <td width="13%"><img src="<{$module_url}>images/icon_01.gif"
                                                         style="float:left; margin-top:5px; margin-right:3px;"
                                                         width="18" height="18">商圈：
                                    </td>
                                    <td width="23%"><{$hotel.city_name}></td>
                                    <td width="9%">地图：</td>
                                    <td width="21%"><img align="left" src="<{$module_url}>images/icon_02.gif"
                                                         style="cursor: pointer;" title="查看大地图"
                                                         onclick="ShowMap(this,'<{$hotel.hotel_google.0}>','<{$hotel.hotel_google.1}>','<{$hotel.hotel_name}>')">
                                    </td>
                                </tr>
                            </table>
                            </p>
                        </div>
                    </div>
                    <div class="righttitle">
                        <table width="100%" border="0" cellspacing="0" cellpadding="5">
                            <tr align="center" class="bld">
                                <td width="18%">房型</td>
                                <td width="10%">床型</td>
                                <td width="10%">均价</td>
                                <td width="12%"><p>直接预订<br> 送现金券</p></td>
                                <td width="12%">直接预订</td>
                                <td width="23%">查询预订参考价</td>
                                <td width="15%">查询预订</td>
                            </tr>
                            <{foreach item=room name=room from=$rooms[$hotel.hotel_id]}>
                                <tr align="center" <{if $smarty.foreach.room.index > 2}>class="hotel<{$hotel.hotel_id}>"
                                    style="display:none;"<{/if}>>
                                    <td width="20%">
                                        <div class="rela"><a href="javascript:void(0);"><{$room.room_type_info}></a>
                                            <div class="abso" style="display:none;">
                                                <table width="500" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td><{$smarty.const._AM_XMARTIN_HOTEL_FLOOR}>
                                                            ：<{$room.room_floor}></td>
                                                        <td><{$smarty.const._AM_XMARTIN_HOTEL_AREA}>：<{$room.room_area}>
                                                            ㎡
                                                        </td>
                                                        <td>房型：<{$room.room_type_info}></td>
                                                    </tr>
                                                    <{if $room.room_bed_info}>
                                                        <tr>
                                                        <td>介绍：<{$room.room_bed_info}></td></tr><{/if}>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                    <td width="11%"><{$bedtype[$room.room_bed_type]}></td>
                                    <td width="13%">
                                        <div class="rela"><a onclick="showPrice(this);"
                                                             style="cursor:pointer;text-decoration:none;">￥ <{$room.room_price}></a>
                                            <{if $check_date.0}>
                                                <div class="abso" style="display:none;">
                                                <table width="300" border="0" cellspacing="0" cellpadding="0">
                                                    <tr><{foreach from=$room.room_prices item=price}>
                                                            <td><{$price.date}></td><{/foreach}></tr>
                                                    <tr><{foreach from=$room.room_prices item=price}>
                                                            <td><{$price.price}></td><{/foreach}></tr>
                                                </table>
                                                </div><{/if}>
                                        </div>
                                    </td>
                                    <td width="12%">￥ <{$room.room_sented_coupon}></td>
                                    <td width="12%"><img src="<{$module_url}>images/btn_01.gif" title="直接预订" border="0"
                                                         style="cursor:pointer;"
                                                         onClick="book(this,<{$hotel.hotel_id}>,<{$room.room_id}>,false);"
                                                         class="book"></td>
                                    <td width="17%">￥ <{$room.room_advisory_range_small}>
                                        —<{$room.room_advisory_range_max}> <{if $room.room_is_today_special}><img
                                            src="<{$module_url}>images/tejia.gif"
                                            style="float:right; margin-right:5px;"
                                            width="18" height="18"><{/if}></td>
                                    <td width="15%"><img src="<{$module_url}>images/btn_02.gif" title="寻找最低价" border="0"
                                                         style="cursor:pointer;"
                                                         onClick="book(this,<{$hotel.hotel_id}>,<{$room.room_id}>,true);"
                                                         class="book"></td>
                                </tr>
                                <{if $smarty.foreach.room.index > 2 && $smarty.foreach.room.last}>
                                    <tr align="right">
                                    <th colspan="7"><a onclick="showHotel(this,'hotel<{$hotel.hotel_id}>');"
                                                       style="cursor:pointer;text-decoration:none;">全部房型 ▼</a></th>
                                    </tr><{/if}>
                            <{/foreach}>
                        </table>
                    </div>
                </div>
            <{/foreach}>
        </div>

        <div class="hoteltitle"></div>
        <div style="background:#F0F7EB; padding:15px;">
            <{$smarty.const._AM_XMARTIN_INFOLINE1}>
        </div>
    </div>
    <input type="hidden" id="check_in_date" value="<{$check_in_date}>"><input type="hidden" id="check_out_date"
                                                                              value="<{$check_out_date}>"><input
            type="hidden" id="module_url"
            value="<{$module_url}>">
    <!--新闻-->
    <!--首页酒店信息-->
    <div class="hotel"><{$martin_hotel_search_left}></div>
</div>
<input type="hidden" id="lat" value=""><input type="hidden" id="lng" value="">
<div style='border: medium none;background-color: rgb(102, 102, 102); position:absolute;visibility:visible; z-index:1;opacity: 0.6; left:0;top: 0;width:100%; height: 1682px; z-index:15;display:none;'
     class='mapbig'>
</div><img id="mapClose" class="mapbig" title="<{$smarty.const._AM_XMARTIN_CLOSE_MAP}>"
           src="<{$xoops_url}>/js/jquery.fancybox/fancy_closebox.png"
           style="top: 55px; z-index: 101; position: absolute;cursor: pointer;display:none;">
<div id='mapbig' class="mapbig"
     style='top:80px; z-index:101;position: absolute;display:none;border-right: 10px solid #F0F7EB;border-bottom: 10px solid #F0F7EB;border-top: 10px solid #F0F7EB;border-left: 10px solid #F0F7EB;'></div>
<!--结束主体--!>
