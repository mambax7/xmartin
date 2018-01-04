<!--面包线-->
<link rel="stylesheet" href="<{$module_url}>css/hotel.css" type="text/css">
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(function ($) {
        $("#sub").click(function () {
            var result = true;
            var review_content = $("#review_content").val();
            var review_purpose = $(".tdnone").find("input[type=radio][checked]").val();
            $(".review_type").each(function () {
                if (!$(this).parent("tr").find("input[type=radio][checked]").val()) result = false;
            });
            if (review_content == '') {
                $("#review_content").focus();
                var result = false;
            }
            if (!review_purpose)var result = false;
            if (result) {
                $("#review").submit();
            } else {
                alert('请认真填写');
            }
        });
        $(".tdnone").click(function () {
            $(this).children("input").attr("checked", true);
        });
    });
</script>
<div class="breadcrums"><span>您所在的位置：<a href="<{$xoops_url}>">首页<a></span> <img width="3" height="5"
                                                                                src="<{$news_url}>images/arrow.png">
    酒店点评
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
            <form action="" method="POST" id="review">
                <{securityToken}><{*//mb*}>
                <h2>打分</h2>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                    <tr class="fwt">
                        <td width="20%">项目</td>
                        <td width="16%">极度不满</td>
                        <td width="16%">不满</td>
                        <td width="16%">一般</td>
                        <td width="16%">满意</td>
                        <td width="16%">特别满意</td>
                    </tr>
                    <{foreach from=$hotel_review_type item=review_type key=key}>
                        <{assign var=r_key value=review_type_$key}>
                        <tr>
                            <td align="center" class="review_type"><{$review_type}></td>
                            <td align="center">1<input type="radio" name="review_type[<{$key}>]" value="1"
                                                       <{if $review.$r_key eq 1}>checked="true"<{/if}>></td>
                            <td align="center">2<input type="radio" name="review_type[<{$key}>]" value="2"
                                                       <{if $review.$r_key eq 2}>checked="true"<{/if}>></td>
                            <td align="center">3<input type="radio" name="review_type[<{$key}>]" value="3"
                                                       <{if $review.$r_key eq 3}>checked="true"<{/if}>></td>
                            <td align="center">4<input type="radio" name="review_type[<{$key}>]" value="4"
                                                       <{if $review.$r_key eq 4}>checked="true"<{/if}>></td>
                            <td align="center">5<input type="radio" name="review_type[<{$key}>]" value="5"
                                                       <{if $review.$r_key eq 5}>checked="true"<{/if}>></td>
                        </tr>
                    <{/foreach}>
                </table>
                <h2>点评</h2>
                <table width="100%" border="0" cellspacing="15" cellpadding="0" class="dtable">
                    <tr>
                        <td width="20%" align="left" class="tdnone"><textarea name="review_content" id="review_content"
                                                                              cols="100"
                                                                              rows="4"><{$review.review_content}></textarea>
                        </td>
                    </tr>
                </table>
                <h2>出行目的</h2>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                    <tr>
                        <{foreach from=$hotel_trip_purpose item=review_purpose key=key}>
                            <td width="20%" align="center" class="tdnone"><input type="radio" name="review_purpose"
                                                                                 value="<{$key}>"
                                                                                 <{if $review.review_purpose eq $key}>checked="true"<{/if}>><{$review_purpose}>
                            </td>
                        <{/foreach}>
                    </tr>
                </table>
                <input type="hidden" value="<{$hotel_id}>" name="<{$hotel_id}>">
                <div style="margin:20px; font-weight:normal; text-align:center;"><input type="button" value="酒店点评保存"
                                                                                        style="margin-left:10px;"
                                                                                        class="button" id="sub"></div>
            </form>
        </div>
    </div>
</div>
<!--结束会员管理-->
