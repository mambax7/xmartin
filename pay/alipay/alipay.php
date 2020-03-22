<?php

/**
 * @alipay
 * @license   http://www.blags.org/
 * @created   :2010年07月05日 22时43分
 * @copyright 1997-2010 The Martin Group
 * @author    Martin <china.codehome@gmail.com>
 * */
require_once XMARTIN_ROOT_PATH . "pay/$order_pay/alipay_service.php";
$config_file = XMARTIN_ROOT_PATH . "pay/$order_pay/config/config.php";
if (file_exists($config_file)) {
    require_once $config_file;
}

$config = ${$order_pay};
if (is_array($config)) {
    foreach ($config as $key => $value) {
        ${$key} = $value;
    }
}

/*$parameter = array(
"service" => "sign_protocol_with_partner", //交易类型，必填实物交易＝trade_create_by_buyer（需要填写物流） 虚拟物品交易＝create_digital_goods_trade_p 捐赠＝create_donate_trade_p
"partner" =>$partner,                                               //合作商户号
"_input_charset" => $_input_charset,                                //字符集，默认为GBK
);*/

$parameter = [
    'service'            => 'create_direct_pay_by_user',
    //交易类型，必填实物交易＝trade_create_by_buyer（需要填写物流） 虚拟物品交易＝create_digital_goods_trade_p 捐赠＝create_donate_trade_p
    'partner'            => $partner,
    //合作商户号
    'agent'              => $agent,
    'return_url'         => XOOPS_URL . $return_url,
    //同步返回
    'notify_url'         => XOOPS_URL . $notify_url,
    //异步返回
    //"order_id"=>$order_id,
    '_input_charset'     => $_input_charset,
    //字符集，默认为GBK
    'subject'            => $order['room_name'],
    //商品名称，必填
    'body'               => $order['room_info'],
    //商品描述，必填
    'out_trade_no'       => $order_id,
    //time() ,                      //商品外部交易号，必填,每次测试都须修改
    //测试
    //"total_fee" => "0.01",
    'total_fee'          => $order['order_pay_money'],
    //商品单价，必填
    'payment_type'       => '1',
    // 商品支付类型 1 ＝商品购买 2＝服务购买 3＝网络拍卖 4＝捐赠 5＝邮费补偿 6＝奖金
    'show_url'           => $order['room_url'],
    //"http://www.buyaby.org/",            //商品相关网站公司
    'seller_email'       => $seller_email,
    //卖家邮箱，必填
    'paymethod'          => 'bankPay',
    //"directPay"
    'defaultbank'        => 'CMB',
    'royalty_type'       => '10',
    //测试
    //"royalty_parameters"=> $xoopsUser->email()."^0.01^酒店预定",
    'royalty_parameters' => $xoopsUser->email() . '^' . $order['order_pay_money'] . '^酒店预定',
];

//var_dump($parameter);
//exit;

$alipay = new alipay_service($parameter, $security_code, $sign_type);
//var_dump($parameter );
$link = $alipay->create_url();
//echo $link;exit;

redirect_header($link, 2, '支付页面跳转中,请稍候....');
