<link rel="stylesheet" href="<{$module_url}>css/hotel.css" type="text/css">
<script type="text/javascript">
    var this_url = '<{$this_url}>';
    jQuery.noConflict();
    jQuery(document).ready(function ($) {
        $("#sub").click(function () {
            var check = true;
            var pwd = $("input[name=passwd]").val();
            var pwd_check = $("input[name=passwd_check]").val();
            var email = $("input[name=email]").val();
            var email_reg = /^([a-zA-Z0-9\._-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
            if (pwd != '' && pwd.length < 6) {
                alert('密码不能小于6位数.');
                $("input[name=passwd]").focus();
                check = false;
                return false;
            } else if (pwd != pwd_check) {
                alert('两次密码输入不一致.');
                $("input[name=passwd_check]").focus();
                check = false;
                return false;
            } else if (!email_reg.test(email)) {
                alert('请输入正确邮箱.');
                $("input[name=email]").focus();
                check = false;
                return false;
            }
            if (check) {
                $("input[name=passwd]").val();
                $("#member").submit();
            }
        });
    });
</script>
<div class="breadcrums"><span>您所在的位置：<a href="<{$xoops_url}>">首页<a></span> <img width="3" height="5"
                                                                                src="<{$news_url}>images/arrow.png">
    我的资料
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
        <form action="<{$member_url}>?info" method="POST" id="member">
            <{securityToken}><{*//mb*}>
            <div class="panel_right">
                <h2>我的资料：</h2>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                    <tr>
                        <td width="11%" align="right"> 会员名称：</td>
                        <td width="36%"><{$user.uname}></td>
                        <td width="10%" align="right"> 会员编号：</td>
                        <td width="43%"><{$user.uid}><input type="hidden" name="uid" value="<{$user.uid}>"></td>
                    </tr>
                    <tr>
                        <td align="right">密码：</td>
                        <td><input name="passwd" type="password" value=""></td>
                        <td align="right">确认密码：</td>
                        <td><input name="passwd_check" type="password" value=""></td>
                    </tr>
                    <tr>
                        <td align="right">姓名：</td>
                        <td><input name="name" type="text" value="<{$user.name}>"></td>
                        <td align="right">手机：</td>
                        <td><input name="phone" type="text" value="<{$user.phone}>"></td>
                    </tr>
                    <tr>
                        <td align="right">邮箱：</td>
                        <td><input name="email" type="text" value="<{$user.email}>"></td>
                        <td align="right">固定电话：</td>
                        <td><input name="telephone[]" type="text" value="<{$user.telephone.0}>" size="4"> - <input
                                    name="telephone[]" type="text" value="<{$user.telephone.1}>" size="4"> <input
                                    name="telephone[]" type="text" value="<{$user.telephone.2}>" size="8"></td>
                    </tr>
                    <tr>
                        <td align="right"> 公司名：</td>
                        <td><input name="company" type="text" value="<{$user.company}>"></td>
                        <td align="right"> 国籍：</td>
                        <td>
                            <select name="nationality">
                                <{foreach from=$UserNationnality key=key item=v}>
                                    <option value="<{$key}>"
                                            <{if $user.nationality eq $key}>selected="selected"<{/if}>><{$v}></option>
                                <{/foreach}>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"> 证件类型：</td>
                        <td><select name="document">
                                <{foreach from=$OrderDocumentType key=key item=v}>
                                    <option value="<{$key}>"
                                            <{if $user.document eq $key}>selected="selected"<{/if}>><{$v}></option>
                                <{/foreach}>
                            </select></td>
                        <td align="right"> 证件号码：</td>
                        <td><input name="document_value" type="text" value="<{$user.document_value}>" size="26"></td>
                    </tr>
                    <tr>
                        <td align="right" style="border:none; margin-top:3px;" valign="top"> 备注：</td>
                        <td colspan="3" style="border:none;"><textarea name="bio" cols="76"
                                                                       rows="4"><{$user.bio}></textarea></td>
                    </tr>
                    </tr>
                </table>
                <div style="margin:20px; font-weight:normal; text-align:center;"><input type="button" value="资料保存"
                                                                                        style="margin-left:10px;"
                                                                                        class="button" id="sub"></div>
            </div>
        </form>
    </div>
    <!--结束会员管理-->
</div>
<!--结束主体--!>
