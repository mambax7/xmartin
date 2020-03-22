<?php

$alipay['partner']        = ''; //合作伙伴ID
$alipay['security_code']  = ''; //安全检验码
$alipay['seller_email']   = ''; //卖家邮箱
$alipay['_input_charset'] = 'UTF8'; //字符编码格式
$alipay['sign_type']      = 'MD5'; //加密方式
$alipay['transport']      = 'http'; //访问模式,你可以根据自己的服务器是否支持ssl访问而选择http以及https访问模式
$alipay['notify_url']     = 'http://www.XXXX./notify_url.php'; // 异步返回地址
$alipay['return_url']     = 'http://www.XXXX/return_url.php'; //同步返回地址
