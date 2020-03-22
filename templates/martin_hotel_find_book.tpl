<style type="text/css">@import "<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick.css";</style>
<script type="text/javascript" src="<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick.js"></script>
<script type="text/javascript" src="<{$module_url}>assets/javascript/jquery/jquery-datepick/jquery.datepick-zh-CN.js"></script>
<script type="text/javascript" src="<{$module_url}>assets/javascript/hotel.js"></script>
<link rel="stylesheet" href="<{$module_url}>css/hotelsearch.css" type="text/css">
<script type="text/javascript">
    var this_url = '<{$this_url}>';
    jQuery.noConflict();
    jQuery(document).ready(function ($) {
        $('.checkdate').datepick({minDate: 0, maxDate: 60, showOnFocus: false, dateFormat: 'yy-mm-dd'});
        $('.checkdate').change(function () {
            if (this.id == 'checkdate1' && $('#checkdate2').val() == '') {
                $('#checkdate2').focus();
            } else {
                $("#datepick-div").hide();
            }
            if (this.id == 'check_in_date' && $('#check_out_date').val() == '') {
                $('#check_out_date').focus();
            } else {
                $("#datepick-div").hide();
            }
        });
        /*$(".selectNum").change(function(){
         var person_exchange = new Number($(".person_exchange").val());
         var Num = new Number($(this).find("option:selected").val());
         var Price = new Number($(this).parent("td").prev("td").text());
         var OTotal = new Number($(this).parent("td").next("td").text());
         var STotal = Num * Price ;
         var Total = new Number($(".total").text());
         Total = Total - OTotal;
         Total = Total + STotal;
         var send_price = 0;var Max_Num = 0;
         var service_total = new Number($(".service_total").text());
         var all_total = Total + service_total - person_exchange;
         $(".send_coupon").each(function(i){
         var coupon = new Number($(this).val());
         var couponNum = new Number($(this).prev("select").find("option:selected").val());
         send_price += coupon * couponNum;
         if(couponNum > Max_Num) Max_Num = couponNum;
         });
         $(this).parent("td").next("td").text(STotal.toFixed(2));$(".total").text(Total.toFixed(2));
         $(".room_total").text(Total.toFixed(2));$(".send_price").text(send_price.toFixed(2));
         $(".all_total").text(all_total.toFixed(2));
         //生成表单
         var extra_person = '';
         for(i = 0 ; i < Max_Num - 1; i ++)
         {
         extra_person += '<input name="extra_person[]"  type="text" size="18">';
         }
         $(".extra_person").html(extra_person);
         });*/
        $(".serviceSelect").blur(function () {
            var person_exchange = new Number($(".person_exchange").val());
            var Total = new Number($(".total").text());
            var Num = new Number($(this).val());
            if (isNaN(Num)) {
                alert('只能输入数字');
                $(this).val('');
                $(this).focus();
            } else {
                var oPrice = new Number($(this).parent("td").prev("td").text());
                var sPrice = new Number($(this).parent("td").next("td").text());
                var service_total = new Number($(".service_total").text());
                service_total = service_total - sPrice;
                sPrice = oPrice * Num;
                service_total = sPrice != 0 ? service_total + sPrice : service_total;
                var all_total = Total + service_total - person_exchange;
                $(".service_total").text(service_total.toFixed(2));
                $(".service_all_total").text(service_total.toFixed(2));
                $(this).parent("td").next("td").text(sPrice.toFixed(2));
                $(".all_total").text(all_total.toFixed(2));
            }
        });
        /*$(".person_exchange").blur(function(){
         var v = new Number($(this).val());
         var all_total = new Number($(".all_total").text());
         if(all_total < v){
         alert('兑换不能超过总金额');$(this).val('');$(this).focus();
         }else if(isNaN(v)){
         alert('只能输入数字');$(this).val('');$(this).focus();
         }else{
         var person_all_price = new Number($(".person_all_price").text());
         var room_total  = new Number($(".room_total").text());
         var service_all_total  = new Number($(".service_all_total").text());
         if(v > person_all_price)
         {
         alert('现金卷不足');$(this).focus();
         var practical = (room_total + service_all_total) - person_all_price ;
         $(this).val(person_all_price);$(".person_exchange").text(person_all_price.toFixed(2));
         $(".all_total").text(practical.toFixed(2));
         }else{
         var practical = (room_total + service_all_total) - v ;
         $(".person_exchange").text(v.toFixed(2));$(".all_total").text(practical.toFixed(2));
         }}
         });*/
        $("#slect_date").click(function () {
            var check_in_date = $.trim($("#check_in_date").val());
            var check_out_date = $.trim($("#check_out_date").val());
            if (check_in_date == '' || check_out_date == '') {
                alert('清选择日期');
                return false;
            }
            var url = this_url + '&check_in_date=' + check_in_date + '&check_out_date=' + check_out_date;
            window.location.href = url;
        });
        $("#sub_user").click(function () {
            var post = '';
            $("#user input[type=text]").not($(this)).each(function () {
                post += this.id + '=' + $(this).val() + '&';
            });
            post += "document=" + $("#document").val();
            $.post('ajax.php?action=saveuser', post, function (data) {
                alert(data);
            });
        });
        $("#sub_order").click(function () {
            var check_in_date = $("#check_in_date").val();
            var name = $("#name").val();
            var document_value = $("#document_value").val();
            var phone = $("#phone").val();
            var username = $("#name").val();
            var telephone = $("#telephone").val();
            var check_out_date = $("#check_out_date").val();
            var room_total = new Number($(".room_total").text());
            if (check_in_date == "") {
                alert("请选择入住日期");
                window.location.hash = "check_in_date";
                $("#check_in_date").focus();
                return false;
            } else if (check_out_date == "") {
                alert("请选择离开日期");
                window.location.hash = "check_out_date";
                $("#check_out_date").focus();
                return false;
            } else if (username == "") {
                alert("请填写姓名");
                window.location.hash = "username";
                $("#username").focus();
                return false;
            } else if (telephone == "" || telephone == "--") {
                alert("请填写固定电话");
                window.location.hash = "telephone";
                $("#telephone").focus();
                return false;
            } else {
                $("#order").submit();
            }
            /*if(room_total == 0)
             {

             var url = this_url + '&check_in_date=' + check_in_date + '&check_out_date=' + check_out_date;
             //alert(s"请选择房间数量.");
             //window.location.href = url;
             }else{
             /*if(name == ""){
             alert("请输入真实名称");window.location.hash="name";$("#name").focus();return false;
             }else if(document_value == ""){
             alert("请输入证件");window.location.hash="document_value";$("#document_value").focus();return false;
             }else if(phone == ""){
             alert("请输入手机");window.location.hash="phone";$("#phone").focus();return false;
             }else{$("#order").submit();}
             }*/
        });
    });
</script>
<div class="breadcrums"><span>您所在的位置：</span>首页</div>
<!--结束面包线-->
<div class="blocknh">
    <!--新闻-->
    <form action="order/order_save.php" method="POST" id="order">
        <{securityToken}><{*//mb*}>
        <div class="leftblock">
            <div class="l_title">选择 <{$hotel.hotel_name}></div>
            <div class="hotellist">
                <div class="hoteltitle bookingtitle btop">
                    <div class="lefttitle">入住日期选择</div>
                </div>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                    <{if $check_arr}>
                        <tr align="center" class="fwt">
                            <td width="33%">日期</td>
                            <td width="43%">价格范围</td>
                            <td width="23%">数量</td>
                            <!--<td width="23%">小计</td>-->
                        </tr>
                        <{assign var=total value=0}>
                        <{assign var=send_price value=0}>
                        <{foreach from=$check_arr item=d}>
                            <{assign var=total value=$total+$room_price.$d.room_price}>
                            <{assign var=send_price value=$send_price+$room_price.$d.room_sented_coupon}>
                            <tr align="center">
                                <td width="33%"><{$d|date_format:"%Y年%m月%d日"}></td>
                                <td width="43%"><{$room_price.$d.room_advisory_range_small}>
                                    — <{$room_price.$d.room_advisory_range_max}></td>
                                <td width="23%"><select name="dateNumber[<{$d}>]" class="selectNum">
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                    </select><input type="hidden" value="<{$room_price.$d.room_sented_coupon}>"
                                                    class="send_coupon"></td>
                            </tr>
                        <{/foreach}>
                    <{else}>
                        <tr align="center" class="fwt">
                            <td width="33%">请选择日期</td>
                            <td width="21%">入住日期</td>
                            <td width="23%">离开日期</td>
                            <td width="23%">提交</td>
                        </tr>
                        <tr align="center">
                            <td width="30%">选择一个时间范围</td>
                            <td width="21%"><input type="text" id="check_in_date" class="checkdate" size="10"></td>
                            <td width="23%"><input type="text" id="check_out_date" class="checkdate" size="10"></td>
                            <td width="23%"><input class="button" id="slect_date" type="button" value="提交"></td>
                        </tr>
                    <{/if}>
                </table>
            </div>
            <{if $check_arr}>
                <div class="hotellist">
                    <div class="hoteltitle bookingtitle">
                        <div class="lefttitle">增值服务</div>
                    </div>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="dtable">
                        <tr align="center" class="fwt">
                            <td width="27%">增值服务名称</td>
                            <td width="19%">单位</td>
                            <td width="18%">价格</td>
                            <td width="17%"><p>数量</p></td>
                            <!--<td width="19%">增值服务小计</td>-->
                        </tr>
                        <{assign var=service_total value=0}>
                        <{foreach from=$hotel_service item=service}>
                            <{assign var=service_total value=$service_total+$service.service_extra_price}>
                            <tr align="center">
                                <td width="27%"><{$service.service_name}></td>
                                <td width="19%"><{$service.service_unit}></td>
                                <td width="18%"><{$service.service_extra_price}></td>
                                <td width="17%"><input name="serviceNum[<{$service.service_id}>]" type="text" size="5"
                                                       value="0" class="serviceSelect"></td>
                            </tr>
                        <{/foreach}>
                        <!--<tr align="center"><td colspan="5" align="left" style="padding-left:15px;" class="tdnone">增值服务合计：<span class="red service_total"><{$service_total}></span>元</td></tr>-->
                    </table>
                </div>
                <!--   <div class="hotellist">
     <div class="hoteltitle bookingtitle"><div class="lefttitle">结算</div></div>
     <div class="listclass">
<{assign var=all_total value=$service_total+$total}>
1、您目前的现金券余额为：<span class="red person_all_price"><{$person_exchange_price}></span>元 ，您打算兑换掉的金额为：<input name="person_exchange" class="person_exchange" type="text" size="6">元；<br>
2、本订房单房费为人民币<span class="red room_total"><{$total}></span>元，增值服务费为人民币为<span class="red service_all_total"><{$service_total}></span>元，您要兑换掉的现金券为人民币<span class="red person_exchange">0.00</span>元，您需要实际支付的金额为人民币<span class="red all_total"><{$all_total}></span>元；<br>
3、通过本次预订，您将获赠现金券金额为人民币<span class="red send_price"><{$send_price}></span>元，将于您退房日期后一个工作日内到帐，注意查收；</div>
   </div>-->
                <div class="hotellist">
                    <div class="hoteltitle bookingtitle">
                        <div class="lefttitle">港捷旅赠品</div>
                    </div>
                    <div class="listclass"><{if $hotel.promotion}><{$hotel.promotion.promotion_description}><{else}>暂时没有奖品赠送活动.<{/if}></div>
                </div>
                <div class="hotellist">
                    <div class="hoteltitle bookingtitle">
                        <div class="lefttitle">酒店特别提示</div>
                    </div>
                    <div class="listclass"><{$hotel.hotel_reminded}></div>
                </div>
                <div class="hotellist">
                    <div class="hoteltitle bookingtitle">
                        <div class="lefttitle">订房人联系信息</div>
                    </div>
                    <table width="100%" border="0" cellspacing="0" id="user" cellpadding="0" class="dtable">
                        <tr>
                            <td width="18%" align="right"><font color="#FF0000">*</font>姓名：</td>
                            <td width="82%"><input name="user[name]" id="name" type="text" size="18"
                                                   value="<{$user.name}>"></td>
                            <!--<td width="18%" align="right"><font color="#FF0000">*</font>证件类型：</td>
        <td width="32%"><select name="user[document]" id="document"><{foreach from=$order_document_type item=document key=key}><option value="<{$key}>" <{if $key eq $user.document}>selected="selected"<{/if}>><{$document}></option><{/foreach}></select></td>-->
                        </tr>
                        <tr>
                            <td align="right"><font color="#FF0000">*</font>固定电话：</td>
                            <td><input name="user[telephone]" id="telephone" type="text" size="18" maxlength="30"
                                       value="<{$user.telephone}>"></td>
                            <!--<td align="right"><font color="#FF0000">*</font>证件号码：</td>
        <td><input name="user[document_value]" id="document_value" type="text" size="18" maxlength="255" value="<{$user.document_value}>"></td>-->
                        </tr>
                        <tr>
                            <td align="right"><font color="#FF0000">*</font>手机：</td>
                            <td><input name="user[phone]" id="phone" type="text" size="18" maxlength="25"
                                       value="<{$user.phone}>"></td>
                            <!--<td colspan="2">如果您是预订港澳酒店：<br>
                            大陆客户请留下港澳通行证或护照号码；<br>
                            港澳或海外客户请留下身份证号；部份酒店可能会对港澳（海外）客户另行加价，请再向客服咨询最新报价。</td>-->
                            <!--    </tr><tr>
                                    <td align="right">其他入住人姓名：</td>
                                    <td class="extra_person">
                                        <input name="extra_person[]"  type="text" size="18">
                                    </td>
                                    <td colspan="2">（注： 除办理入住手续的客人外，其他每间客房需留下一名入住人姓名； ）</td>-->
                        </tr>
                        <tr>
                            <td colspan="4" align="center" class="tdnone">
                                <input type="button" value="将入住人详情保存为会员信息" style="margin-left:10px;" class="button"
                                       id="sub_user">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" align="center" class="tdnone">&nbsp;</td>
                        </tr>
                    </table>
                </div>
                <input type="hidden" name="hotel_id" value="<{$hotel_id}>">
                <input type="hidden" name="room_id" value="<{$room_id}>">
                <input type="hidden" name="check_in_date" value="<{$check_in_date}>">
                <input type="hidden" name="check_out_date" value="<{$check_out_date}>">
                <div style="margin:20px; font-weight:normal; text-align:center;"><input type="button"
                                                                                        value="<{if $smarty.get.isFind eq 'true'}>查询<{else}>预定<{/if}>"
                                                                                        style="margin-left:10px;"
                                                                                        class="button"
                                                                                        id="sub_order"><input
                            type="button" value="取消订单" style="margin-left:10px;" class="button"
                            onclick="window.history.go(-1);"></div>
                <input type="hidden" name="isFind" value="<{$smarty.get.isFind}>">
                <input type="hidden" name="Form_Validate" value="<{$Form_Validate}>">
            <{/if}>
    </form>
</div>
<!--新闻-->
<!--首页酒店信息-->
<div class="hotel"><{$martin_hotel_search_left}></div>
<!--结束首页酒店信息-->
</div>
<!--结束主体--!>
