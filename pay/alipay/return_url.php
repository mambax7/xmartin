<?php
include __DIR__ . '/../../../../mainfile.php';
include XOOPS_ROOT_PATH . '/modules/martin/include/common.php';
if (!defined('MODULE_URL')) {
    define('MODULE_URL', XOOPS_URL . '/modules/martin/');
}
if (!defined('ALIPAY_ROOT_PATH')) {
    define('ALIPAY_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/martin/pay/alipay/');
}
require_once ALIPAY_ROOT_PATH . 'alipay_notify.php';
require_once ALIPAY_ROOT_PATH . 'config/config.php';

$config = $alipay;
if (is_array($config)) {
    foreach ($config as $key => $value) {
        ${$key} = $value;
    }
}
//alipay返回参数
$return_arr = $_GET;
if (is_array($return_arr)) {
    foreach ($return_arr as $key => $value) {
        ${$key} = is_numeric($value) ? round($value, 2) : null;
        ${$key} = is_string($value) ? trim($value) : ${$key};
    }
}

function log_result($word)
{
    $fp = fopen('log.txt', 'a');
    flock($fp, LOCK_EX);
    fwrite($fp, $word . '：执行日期：' . strftime('%Y%m%d%H%I%S', time()) . "\t\n");
    flock($fp, LOCK_UN);
    fclose($fp);
    //chmod('log.txt',777);
}

//var_dump($out_trade_no);exit;

$order_id = (int)$out_trade_no;
global $xoopsUser;
$cartHandler = xoops_getModuleHandler('cart', 'martin');
$order       = $cartHandler->GetOrderInfo($order_id);
if (!$order) {
    redirect_header(XOOPS_URL, 1, '非法闯入.');
}
if ($cartHandler->CheckOrderClose($order_id)) {
    redirect_header(XOOPS_URL, 1, '非法闯入.');
}

$alipay        = new alipay_notify($partner, $security_code, $sign_type, $_input_charset, $transport);
$verify_result = $alipay->return_verify();
//echo urldecode($_SERVER["QUERY_STRING"]);
if ($verify_result) {
    //更新订单状态
    /*if($order['order_pay_money'] != $total_fee)
    {
        redirect_header(XOOPS_URL,1,'非法访问.');
    }*/
    $cartHandler->UpdateOrderStatus($order_id, 7);
    $msg        = '支付成功,我们已经收到您的订单,我们会尽快为您定房.';
    $change_url = XOOPS_URL . '/hotel/';
    //echo "success";
    //这里放入你自定义代码,比如根据不同的trade_status进行不同操作
    log_result('verify_success'); //将验证结果存入文件
} else {
    $msg        = '支付失败,为了尽快订房请您及时付款.';
    $change_url = MODULE_URL . 'pay.php?order_id=' . $order_id;
    //这里放入你自定义代码，这里放入你自定义代码,比如根据不同的trade_status进行不同操作
    log_result('verify_failed');
}
redirect_header($change_url, 2, $msg);
