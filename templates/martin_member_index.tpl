<link rel="stylesheet" href="<{$module_url}>css/hotel.css" type="text/css">
<script type="text/javascript">
    function pay_confirm(ele, order_id) {
        if (order_id > 0) {
            jQuery(ele).hide();
            jQuery(ele).next('img').show();
            jQuery.getJSON('<{$module_url}>ajax.php?action=PayConfirm', {order_id: order_id}, function (data) {
                jQuery(ele).next('img').hide();
                alert(data.msg);
                window.location.href = data.url;
            });
        }
    }
</script>
<div class="breadcrums"><span>您所在的位置：<a href="<{$xoops_url}>">首页<a></span> <img width="3" height="5"
                                                                                src="<{$news_url}>images/arrow.png"> <{if $action eq 'order'}>我的订单<{else}>用户中心<{/if}>
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
            <h2>我的订单：</h2>
            <div>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                    <tr class="bld">
                        <td width="7%"> 订单号</td>
                        <td width="8%"> 订房模式</td>
                        <td width="19%"> 酒店</td>
                        <td width="9%"> 房型</td>
                        <td width="12%"> 业务流程状态</td>
                        <td width="18%"> 业务流程状态操作时间</td>
                        <td width="18%"> 操作</td>
                        <td width="9%"> 详情</td>
                    </tr>
                    <{foreach from=$orders item=order name=order}>
                        <tr>
                            <td> <{$order.order_id}> </td>
                            <td> <{$order.order_type}> </td>
                            <td><a href="<{$order.hotel_url}>"><{$order.hotel_name}></a></td>
                            <td> <{$order.room_type_info}> </td>
                            <td> <{$order.order_status}></td>
                            <td> <{$order.order_status_time|date_format:"%Y-%m-%d %H:%M:%S"}> </td>
                            <td> <{if $order.order_status_int eq 1}>
                                    等待客服查询房价
                                <{elseif $order.order_status_int eq 2}>
                                    <a href="<{$member_url}>?order_modify&amp;order_id=<{$order.order_id}>">查看最新房价</a>
                                <{elseif $order.order_status_int eq 6}>
                                    <a href="<{$module_url}>pay.php?order_id=<{$order.order_id}>">支付房款</a>
                                    <{if $order.order_pay neq 'alipay' && $order.order_pay}>
                                        <a href="###" onclick="pay_confirm(this,<{$order.order_id}>);">确认支付</a>
                                        <img src="<{$module_url}>images/load.gif" style="display:none;">
                                    <{/if}>
                                <{/if}>
                            </td>
                            <td><a href="<{$member_url}>?order_info&amp;order_id=<{$order.order_id}>">订单详情</a></td>
                        </tr>
                    <{/foreach}>
                </table>
                <div class="page"><{$pagenav}></div>
            </div>
        </div>
    </div>
    <!--结束会员管理-->
</div>
<!--结束主体--!>
