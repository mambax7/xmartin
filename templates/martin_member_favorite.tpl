<!--面包线-->
<link rel="stylesheet" href="<{$module_url}>css/hotel.css" type="text/css">
<div class="breadcrums"><span>您所在的位置：<a href="<{$xoops_url}>">首页<a></span> <img width="3" height="5"
                                                                                src="<{$news_url}>images/arrow.png">
    我的收藏
</div>
<!--结束面包线-->
<div class="blocknh">
    <!--会员管理-->
    <div class="panel">
        <div class="panel_left">
            <ul>
                <{if $isAdmin}>
                    <li><a href="<{$xoops_url}>/admin.php"><b>进入后台</b></a></li><{/if}>
                <li <{if $action eq 'info'}>class="show"<{/if}>><a href="<{$member_url}>?info">我的资料</a></li>
                <li <{if $action eq 'order'}>class="show"<{/if}>><a href="<{$member_url}>?order">我的订单</a></li>
                <li <{if $action eq 'coupon'}>class="show"<{/if}>><a href="<{$member_url}>?coupon">我的现金券</a></li>
                <li <{if $action eq 'lived'}>class="show"<{/if}>><a href="<{$member_url}>?lived">我住过的酒店</a></li>
                <li <{if $action eq 'favorite'}>class="show"<{/if}>><a href="<{$member_url}>?favorite">我的收藏</a></li>
                <li><a href="<{$xoops_url}>/user.php?op=logout">退出登录</a></li>
            </ul>
        </div>
        <div class="panel_right">
            <h2>我的收藏：</h2>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                <tr class="fwt">
                    <td width="11%"> 序号</td>
                    <td width="16%"> 城市</td>
                    <td width="40%"> 商务区</td>
                    <td width="33%"> 酒店名称</td>
                </tr>
                <{foreach from=$hotels item=hotel name=hotel}>
                    <tr>
                        <td align="center"> <{$hotel.hotel_id}> </td>
                        <td align="center"><!--<a href="<{$hotel.hotel_city_alias}>/">-->
                            <{$hotel.hotel_city}><!--</a>--></td>
                        <td align="center"> <{$hotel.hotel_city_id}> </td>
                        <td align="center"><a href="<{$hotel.url}>"><{$hotel.hotel_name}></a></td>
                    </tr>
                <{/foreach}>
            </table>
            <div class="page"><{$pagenav}></div>
        </div>
    </div>
    <!--结束会员管理-->
</div>
