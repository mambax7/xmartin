<div class="hotel hotel_height">
    <style type="text/css">@import "<{$block.module_url}>javascript/jquery/jquery-datepick/jquery.datepick.css";</style>
    <script type="text/javascript"
            src="<{$block.module_url}>javascript/jquery/jquery-datepick/jquery.datepick.js"></script>
    <script type="text/javascript"
            src="<{$block.module_url}>javascript/jquery/jquery-datepick/jquery.datepick-zh-CN.js"></script>
    <script type="text/javascript" src="<{$block.module_url}>javascript/hotel.js"></script>
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
    <form action="<{$block.module_url}>search.php" id="Search" method="GET">
        <div class="quicksearch"><h2>快速搜索</h2>
            <div class="searchlist">
                <p>城市：
                    <{foreach from=$block.cityList item=city key=key}>
                        <input name="City" id="city<{$key}>" class="radio" type="radio" value="<{$key}>"
                               <{if $smarty.get.City eq $key}>checked="true"<{/if}>>
                        <label for="city<{$key}>"><{$city}></label>
                    <{/foreach}>
                </p>
                <ul>
                    <li>入住日期：<input type="text" class="inputbox checkdate requied" title="入住日期" id="checkdate1"
                                    name="CheckDate[]" value="<{$smarty.get.CheckDate.0}>"></li>
                    <li>退房日期：<input type="text" class="inputbox checkdate requied" title="退房日期" id="checkdate2"
                                    name="CheckDate[]" value="<{$smarty.get.CheckDate.1}>"></li>
                    <li><{$smarty.const._AM_MARTIN_PRICE_RANGE}>：<input type="text" name="price[]" size="3"
                                                                        value="<{$smarty.get.price.0}>">-<input
                                type="text" name="price[]" size="3"
                                value="<{$smarty.get.price.1}>"></li>
                    <li>酒店位置：<input type="text" class="inputbox" name="HotelAddress"
                                    value="<{$smarty.get.HotelAddress}>"></li>
                    <li style="width:100%;"><{$smarty.const._AM_MARTIN_HOTEL_NAME}>：<input type="text"
                                                                                           style="width:160px;"
                                                                                           class="inputbox"
                                                                                           name="HotelName"
                                                                                           value="<{$smarty.get.HotelName}>">
                    </li>
                </ul>
                <p>星级：
                    <input name="HotelStar" type="radio" class="radio" value="0" id="HotelStar0" checked='checked'>
                    <label for="HotelStar0">全部</label>
                    <{foreach from=$block.hotelrank key=key item=rank}>
                        <input name="HotelStar" type="radio" class="radio" value="<{$key}>" id="HotelStar<{$key}>"
                               <{if $smarty.get.HotelStar eq $key}>checked="true"<{/if}>>
                        <label for="HotelStar<{$key}>"><{$rank}></label>
                    <{/foreach}>
                </p>
            </div>
            <div class="searchbutton"><input type="button" value="酒店查询" style="margin-left:10px;" class="button"
                                             onclick="SearchSub(this)"><input type="button" value="人工查询"
                                                                               style="margin-left:10px;"
                                                                               class="button"></div>
        </div>
    </form>
    <div id=con>
        <ul id=tags>
            <li class=selectTag><a onClick="selectTag('auction0',this)" href="javascript:void(0)">今日特价</A></li>
            <li><A onClick="selectTag('auction1',this)" href="javascript:void(0)">酒店行情/导购</A></li>
            <li><A onClick="selectTag('auction2',this)" href="javascript:void(0)">拍卖区</A></li>
            <li><A onClick="selectTag('auction3',this)" href="javascript:void(0)">团购区</A></li>
        </ul>
        <div id=auction>
            <div class="auction selectTag" id=auction0>
                <div class="list">
                    <ul>
                        <{foreach from=$block.hotel_today_special_rows item=special}>
                            <li><a href="<{$special.url}>"><{$special.title}></a></li>
                        <{/foreach}>
                    </ul>
                </div>
            </div>
            <div class="auction" id=auction1>
                <div class="list">
                    <ul>
                        <{foreach from=$block.hotel_guide_rows item=guide}>
                            <li><a href="<{$guide.url}>"><{$guide.title}></a></li>
                        <{/foreach}>
                    </ul>
                </div>
            </div>
            <div class="auction" id=auction2>
                <div class="list">
                    <ul>
                        <{foreach from=$block.auctionList key=key item=auction}>
                            <li>
                                <a href="<{$block.module_url}>auction.php/auction-<{$auction.auction_id}><{$hotel_static_prefix}>"
                                   title="<{$auction.auction_name}>"><{$auction.auction_name}></a></li>
                        <{/foreach}>
                    </ul>
                </div>
            </div>
            <div class="auction" id=auction3>
                <div class="list">
                    <ul>
                        <{foreach from=$block.groupList key=key item=group}>
                            <li>
                                <a href="<{$block.module_url}>group.php/group-<{$group.group_id}><{$hotel_static_prefix}>"
                                   title="<{$group.group_name}>"><{$group.group_name}></a></li>
                        <{/foreach}>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
