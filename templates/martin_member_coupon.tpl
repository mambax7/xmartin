<!--面包线-->
<link rel="stylesheet" href="<{$module_url}>css/hotel.css" type="text/css">
<div class="breadcrums"><span>您所在的位置：<a href="<{$xoops_url}>">首页<a></span> <img width="3" height="5"
                                                                                src="<{$news_url}>images/arrow.png">
    我的现金券
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
            <h2>我的现金券：</h2>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                <tr class="fwt">
                    <td width="6%"> 序号</td>
                    <td width="23%"> 日期</td>
                    <td width="11%"> 事项</td>
                    <td width="12%"> 赠送现金券</td>
                    <td width="14%"> 现金券抵扣</td>
                    <td width="22%"> 赠送类型</td>
                    <td width="12%"> 可用余额</td>
                </tr>
                <{foreach from=$coupons item=coupon name=coupon}>
                    <tr>
                        <td align="center"> <{$coupon.user_coupon_id}> </td>
                        <td align="center"> <{$coupon.sented_time|date_format:"%Y年%m月%d日 %H:%M:%S"}> </td>
                        <td align="center"> <{$coupon.hotel_name}> </td>
                        <td align="center"><span class="red"><{$coupon.coupon}></span></td>
                        <td align="center"><span class="red"><{$coupon.used_coupon}></span></td>
                        <td align="center"> <{$coupon_types[$coupon.coupon_type]}> </td>
                        <td align="center"><span class="red"><{$coupon.total_coupon}></span></td>
                    </tr>
                <{/foreach}>
            </table>
            <div class="page"><{$pagenav}></div>
        </div>
    </div>
    <!--结束会员管理-->
</div>
