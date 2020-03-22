<link rel="stylesheet" href="<{$module_url}>css/hotel.css" type="text/css">
<!--<style type="text/css">
    @import url("http://www.google.com/uds/css/gsearch.css");
    @import url("http://www.google.com/uds/solutions/localsearch/gmlocalsearch.css");
</style> -->
<link rel="stylesheet" type="text/css" href="/js/jquery.fancybox/jquery.fancybox.css" media="screen">
<script type="text/javascript" src="/js/jquery.fancybox/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="/js/jquery.fancybox/jquery.fancybox-1.2.1.pack.js"></script>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<{$googleApi}>" type="text/javascript"></script>
<style type="text/css">@import "<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick.css";</style>
<script type="text/javascript" src="<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick.js"></script>
<script type="text/javascript" src="<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick-zh-CN.js"></script>
<script type="text/javascript" src="<{$module_url}>assets/javascript/hotel.js"></script>
<script type="text/javascript">
    var lat = <{$hotel.hotel_google.0}> ;
    var lng = <{$hotel.hotel_google.1}> ;
    var hotel_name = ["<{$hotel.hotel_name}>"];
    var message = ["<{$hotel.hotel_description}>"];
    //var ImgStr = '<img src="<{$module_url}>img.php?id=<{$hotel.hotel_icon}>&w=50&h=50" width="50" height="50" title="<{$hotel.hotel_name}>"">';
    var ImgStr = '';
    jQuery.noConflict();
    jQuery(document).ready(function ($) {
        $(".light").fancybox();
        $('.checkdate').datepick({minDate: 0, maxDate: 30, showOnFocus: false, dateFormat: 'yy-mm-dd'});
        $('.checkdate').change(function () {
            if (this.id == 'checkdate1' && $('#checkdate2').val() == '')
                $('#checkdate2').focus();
            else
                $("#datepick-div").hide();
        });
        $("#big").click(function () {
            var window_width = screen.width;
            var left_width = Number(window_width - 500) / 2;
            $("#mapbig").css('left', left_width + 'px');
            $(".mapbig").show();
            initialize('mapbig', 500, 500, true);
        });
        var ii = 0;
        $(".small").each(function (i) {
            ii = ii + 1;
            if (i > 2)$(this).hide();
        }).click(function () {
            $(".small").find("img").removeClass("show");
            $(this).find("img").addClass("show");
            $(".image").find("img").attr("src", '<{$module_url}>images/loading.gif');
            var midlesrc = $(this).find("img").attr('midlesrc');
            var title = $(this).find("img").attr('title');
            var bigsrc = $(this).find("img").attr('bigsrc');
            $(".image").find("img").attr("src", midlesrc).attr('title', title);
            $(".image").find("a").attr("href", bigsrc).attr('title', title);
        }).hover(function () {
            $(".small").find("img").removeClass("show");
            $(this).find("img").addClass("show");
            $(".image").find("img").attr("src", '<{$module_url}>images/loading.gif');
            var midlesrc = $(this).find("img").attr('midlesrc');
            var title = $(this).find("img").attr('title');<{$module_url}>
            var bigsrc = $(this).find("img").attr('bigsrc');
            $(".image").find("img").attr("src", midlesrc).attr('title', title);
            $(".image").find("a").attr("href", bigsrc).attr('title', title);
        });
        var maxp = Math.ceil(ii / 3);
        $("#next").click(function () {
            var p = Number($("#p").val());
            thisp = p + 1;
            thisp = thisp >= maxp ? maxp - 1 : thisp;
            $("#p").val(thisp);
            min = (thisp * 3) - 1;
            max = (thisp + 1 ) * 3 - 1;
            $(".small").each(function (i) {
                if (i > min && i <= max)
                    $(this).show();
                else
                    $(this).hide();
            });
        });
        $("#prev").click(function () {
            var p = Number($("#p").val());
            thisp = p - 1;
            thisp = thisp < 0 ? 0 : thisp;
            $("#p").val(thisp);
            min = (thisp * 3) - 1;
            max = (thisp + 1 ) * 3 - 1;
            $(".small").each(function (i) {
                if (i > min && i <= max)
                    $(this).show();
                else
                    $(this).hide();
            });
        });
        //$("#bigclose").click(function(){$(".mapbig").hide();});
    });
    var width = 200;
    var height = 189;
    window.onload = function () {
        initialize('gmap', width, height, false);
    };
    //Event.observe(window, "load",initialize);
    //google.setOnLoadCallback(initialize);
</script>

<!--主体-->
<!--面包线-->
<div class="breadcrums"><span>您所在的位置：</span>首页</div>
<!--结束面包线-->

<div class="blocknh">
    <!--新闻-->
    <div class="leftblock">
        <div class="intro_title">
            <div class="intro_left"><{$hotel.hotel_name}>
                <p><{$hotel.hotel_description}></p>
            </div>
            <div class="intro_right">
                <div id="gmap"></div>
                <div align="right"
                     style="background:url(/modules/xmartin/images/pop_map_btn.gif) no-repeat scroll right center transparent; cursor: pointer;margin-top: 5px;"
                     title="查看大地图" id="big">放大&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
            </div>
        </div>
        <div class="hotellist">
            <div class="listclass">
                <div class="listtop">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="14%" align="right">酒店提醒：</td>
                            <td colspan="3" width="37%"><{$hotel.hotel_environment}></td>
                            <td width="49%" rowspan="6" valign="top" align="right" style="border:0;">
                                <div class="imgshow">
                                    <div class="image"><a class="light" rel="group" title="<{$hotel.hotel_image.0.alt}>"
                                                          href="<{$module_url}>i.php?id=<{$hotel.hotel_image.0.filename}>"><img
                                                    src="<{$module_url}>img.php?id=<{$hotel.hotel_image.0.filename}>&w=250&h=154"
                                                    width="250" height="154" title="<{$hotel.hotel_image.0.alt}>"></a>
                                    </div>
                                    <input type="hidden" id="p" value="0">
                                    <div class="smallimg">
                                        <ul>
                                            <li style="width:15px; margin:0;cursor:pointer;" id="prev"><img
                                                        src="<{$module_url}>images/left.gif" width="15" height="25"
                                                        border="0"></li>
                                            <{foreach item=img name=img from=$hotel.hotel_image}>
                                                <{*if !$smarty.foreach.img.first*}>
                                                <li class="small" style="cursor:pointer;"><img
                                                            src="<{$module_url}>img.php?id=<{$img.filename}>&w=45&h=25"
                                                            title="<{$img.alt}>" width="40" height="25"
                                                            midlesrc="<{$module_url}>img.php?id=<{$img.filename}>&w=250&h=154"
                                                            bigsrc="<{$module_url}>i.php?id=<{$img.filename}>"
                                                            <{if $smarty.foreach.img.first}>class="show"<{/if}>>
                                                </li>
                                                <{*/if*}>
                                            <{/foreach}>
                                            <li style="width:15px; margin:0;cursor:pointer; float:right; margin-right:10px; _margin-right:6px;"
                                                id="next"><img src="<{$module_url}>images/right.gif"
                                                               width="15" height="25" border="0"></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">行政区：</td>
                            <td colspan="3"><{$hotel.city_name}></td>
                        </tr>
                        <tr>
                            <td align="right">开业时间：</td>
                            <td colspan="3"><{$hotel.hotel_open_time|date_format:"%Y"}>年开业</td>
                        </tr>
                        <tr>
                            <td align="right">地址：</td>
                            <td colspan="3"><{$hotel.hotel_address}></td>
                        </tr>
                        <tr>
                            <td align="right">联系方式：</td>
                            <td colspan="3"><{$hotel.hotel_telephone}></td>
                        </tr>
                        <tr>
                            <td align="right"><{$smarty.const._AM_XMARTIN_THE_NUMBER_OF_ROOMS}>:</td>
                            <td colspan="3"><{$hotel.hotel_room_count}></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="hotellist">
                <div class="listclass">
                    <!--<div class="list_left">--><{$hotel.hotel_info|htmlspecialchars_decode}><!--</div>-->
                    <!--       <div class="list_right">
       <p style="color:#666666;">点评指数</p>
       <p style="color:#FF0000; font-size:18px; font-weight:bold; margin:8px 0;"><img src="<{$module_url}>images/line.gif" width="100" height="12" style="margin-right:5px;">6.4</p>
       <p style="color:#3F7E15">329人点评</p>
     </div>-->
                </div>
            </div>
            <div class="hotellist">
                <div class="hoteltitle bookingtitle">
                    <div class="lefttitle">房型与房价</div>
                </div>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                    <tr align="center" class="fwt">
                        <td width="21%">房型</td>
                        <td width="9%">床型</td>
                        <td width="14%">直接预订价</td>
                        <td width="12%"><p>直接预订<br>
                                送现金券</p></td>
                        <td width="12%">直接预定</td>
                        <td width="18%">直接预定参考价</td>
                        <td width="14%">查询预定</td>
                    </tr>
                    <{foreach from=$hotel.room item=room}>
                        <tr align="center">
                            <td width="21%"><{$room.room_type_info}></td>
                            <td width="9%"><{$room.room_bed_type}></td>
                            <td width="14%"><{$room.room_price}></td>
                            <td width="12%"><{$room.room_sented_coupon}></td>
                            <td width="12%"><img src="<{$module_url}>images/btn_01.gif" title="直接预订" border="0"
                                                 style="cursor:pointer;"></td>
                            <td width="18%"><{$room.room_advisory_range_small}>-<{$room.room_advisory_range_max}></td>
                            <td width="14%"><img src="<{$module_url}>images/btn_02.gif" title="寻找最低价" border="0"
                                                 style="cursor:pointer;"></td>
                        </tr>
                    <{/foreach}>
                </table>
            </div>
            <div class="hotellist">
                <div class="hoteltitle bookingtitle">
                    <div class="lefttitle">酒店设施与服务</div>
                </div>
                <div class="listclass"><{$hotel.hotel_facility}></div>
            </div>
            <div class="hotellist">
                <div class="hoteltitle bookingtitle">
                    <div class="lefttitle">增值服务</div>
                </div>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                    <tr align="center" class="fwt">
                        <td width="21%">增值服务项目</td>
                        <td width="9%"><{$smarty.const._AM_XMARTIN_SERVICE_UNIT}></td>
                        <td width="14%"><{$smarty.const._AM_XMARTIN_PRICE}></td>
                        <td width="12%"><p>说明</p></td>
                        <td width="12%">订购数量</td>
                    </tr>
                    <{foreach from=$hotel.service item=service}>
                        <tr align="center">
                            <td width="21%"><{$service.service_type_name}></td>
                            <td width="9%"><{$service.service_unit}></td>
                            <td width="14%"><{$service.service_extra_price}></td>
                            <td width="12%"><{$service.service_instruction}></td>
                            <td width="12%"><label><input name="service[<{$service.service_id}>]" type="text" size="5"
                                                          value=""></label></td>
                        </tr>
                    <{/foreach}>
                </table>
            </div>
            <div class="hotellist">
                <div class="hoteltitle bookingtitle">
                    <div class="lefttitle">赠送服务</div>
                </div>
                <div class="listclass"><{$hotel.promotion.promotion_description}></div>
            </div>
            <div class="hotellist">
                <div class="hoteltitle bookingtitle">
                    <div class="lefttitle">酒店特别提示</div>
                </div>
                <div class="listclass"><{$hotel.hotel_reminded}></div>
            </div>
        </div>
        <div class="hoteltitle">
        </div>
    </div>
    <!--新闻-->
    <!--首页酒店信息-->
    <div class="hotel">
        <{$martin_hotel_search_left}>
    </div>
    <!--结束首页酒店信息-->
</div>

<div style='border: medium none;background-color: rgb(102, 102, 102); position:absolute;visibility:visible; z-index:1;opacity: 0.6; left:0;top: 0;width:100%; height: 1682px; z-index:15;display:none;'
     class='mapbig'></div>
<div id='mapbig' class="mapbig" style='top:80px; z-index:101;position: absolute;display:none;'></div>
